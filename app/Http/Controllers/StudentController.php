<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\SchoolFeePayment;
use App\Models\Result;
use App\Models\SchoolFee;
use App\Models\SchoolSession;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display the student dashboard
     */
    public function dashboard(): View
    {
        $authUser = Auth::user();
        $student = Student::with('user')->where('user_id', $authUser->id)->first();
       
        // Check if student record exists
        if (!$student) {
            return view('student.dashboard')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'registeredCourses' => 0,
                'currentCGPA' => 0,
                'outstandingFees' => 0,
                'currentLevel' => 'N/A',
                'recentActivities' => collect(),
                'upcomingDeadlines' => collect(),
                'student' => null
            ]);
        }
        
        // Get student statistics
        $registeredCourses = CourseRegistration::where('student_id', $student->id)
            ->whereHas('semester', function($query) {
                $query->where('is_current', true);
            })
            ->count();
            
        // Use the new GPA calculation method
        $currentCGPA = $student->calculateGPA() ?? 0;
        
        $currentLevel = $student->current_level ?? ($student->level->name ?? '100 Level');
        
        // Get outstanding fees
        $outstandingFees = $this->getOutstandingFees($student);
        
        // Recent activities
        $recentActivities = collect()
            ->merge(
                CourseRegistration::where('student_id', $student->id)
                    ->latest()
                    ->limit(3)
                    ->get()
                    ->map(function($reg) {
                        return [
                            'type' => 'Course Registration',
                            'description' => "Registered for {$reg->course->title}",
                            'date' => $reg->created_at,
                            'status' => $reg->status
                        ];
                    })
            )
            ->merge(
                SchoolFeePayment::where('student_id', $student->id)
                    ->latest()
                    ->limit(3)
                    ->get()
                    ->map(function($payment) {
                        return [
                            'type' => 'Fee Payment',
                            'description' => "Paid ₦" . number_format($payment->amount),
                            'date' => $payment->created_at,
                            'status' => $payment->status
                        ];
                    })
            )
            ->sortByDesc('date')
            ->take(5);
        
        return view('student.dashboard', compact(
            'authUser',
            'student',
            'registeredCourses',
            'currentCGPA',
            'outstandingFees',
            'currentLevel',
            'recentActivities'
        ));
    }
    
    /**
     * Display student biodata
     */
    public function biodata(): View
    {
        $authUser = Auth::user();
        $student = Student::with('user', 'level', 'programme')->where('user_id', $authUser->id)->first();
        
        if (!$student) {
            return view('student.biodata')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'authUser' => $authUser,
                'student' => null
            ]);
        }
        
        return view('student.biodata', compact('authUser', 'student'));
    }
    
    /**
     * Update student biodata
     */
    public function updateBiodata(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:500',
            'gender' => 'nullable|in:male,female',
            'country' => 'nullable|string|max:100',
            'state_of_origin' => 'nullable|string|max:100',
            'lga' => 'nullable|string|max:100'
        ]);
        
        $authUser = Auth::user();
        $student = Student::where('user_id', $authUser->id)->with('programme')->first();
        
        if (!$student) {
            return redirect()->route('student.biodata')
                ->with('error', 'Student profile not found. Please contact the administration to complete your student registration.');
        }
        
        try {
            DB::transaction(function() use ($request, $authUser, $student) {
                // Update user information
                $user = User::find($authUser->id);
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email
                ]);
                
                // Update student information with available fields
                $student->update([
                    'phone' => $request->phone,
                    'date_of_birth' => $request->date_of_birth,
                    'address' => $request->address,
                    'gender' => $request->gender,
                    'country' => $request->country,
                    'state_of_origin' => $request->state_of_origin,
                    'lga' => $request->lga
                ]);
            });
            
            return redirect()->route('student.biodata')
                ->with('success', 'Biodata updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->route('student.biodata')
                ->with('error', 'An error occurred while updating your biodata. Please try again.');
        }
    }
    
    /**
     * Update student photo
     */
    public function updatePhoto(Request $request): JsonResponse
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $authUser = Auth::user();
        $student = Student::where('user_id', $authUser->id)->first();
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student profile not found. Please contact the administration to complete your student registration.'
            ], 404);
        }
        
        try {
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($student->photo && Storage::exists('public/student-photos/' . $student->photo)) {
                    Storage::delete('public/student-photos/' . $student->photo);
                }
                
                // Store new photo
                $photoName = time() . '_' . ($student->matric_number ?? $student->id) . '.' . $request->photo->extension();
                $request->photo->storeAs('public/student-photos', $photoName);
                
                $student->update(['passport' => $photoName]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Photo updated successfully!',
                    'photo_url' => Storage::url('student-photos/' . $photoName)
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'No photo uploaded'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while uploading your photo. Please try again.'
            ], 500);
        }
    }
    
    /**
     * Display current course registration
     */
    public function courseRegistration(): View
    {
        $authUser = Auth::user();
        $student = Student::where('user_id', $authUser->id)->with(['programme', 'level'])->first();
        
        if (!$student) {
            return view('student.course-registration')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'authUser' => $authUser,
                'student' => null,
                'availableCourses' => collect(),
                'registeredCourses' => collect(),
                'registeredCourseIds' => [],
                'currentSemester' => null,
                'canRegister' => false
            ]);
        }
        
        // Get current semester
        $currentSemester = Semester::where('is_current', true)->first();
        
        if (!$currentSemester) {
            return view('student.course-registration')->with([
                'error' => 'No active semester found. Please contact the administration.',
                'authUser' => $authUser,
                'student' => $student,
                'availableCourses' => collect(),
                'registeredCourses' => collect(),
                'registeredCourseIds' => [],
                'currentSemester' => null,
                'canRegister' => false
            ]);
        }
        
        // Check registration deadline
        $canRegister = true;
        $deadlineMessage = null;
        
        if ($currentSemester->registration_end_date && now() > $currentSemester->registration_end_date) {
            $canRegister = false;
            $deadlineMessage = 'Course registration has closed for this semester.';
        }
        
        // Check fee payment status before allowing registration
        $hasPaidFees = $this->checkFeePayment($student);
        if (!$hasPaidFees && $canRegister) {
            $canRegister = false;
            return view('student.course-registration')->with([
                'error' => 'You must pay your school fees to register for courses.',
                'authUser' => $authUser,
                'student' => $student,
                'availableCourses' => collect(),
                'registeredCourses' => collect(),
                'registeredCourseIds' => [],
                'currentSemester' => $currentSemester,
                'feePaymentRequired' => true,
                'canRegister' => false
            ]);
        }
        
        // Get available courses for current level and semester
        $availableCourses = Course::where('programme_id', $student->programme_id)
            ->when($student->level_id, function($query) use ($student) {
                $query->where('level_id', $student->level_id);
            })
            ->where('semester_id', $currentSemester->id)
            ->with(['level', 'semester'])
            ->orderBy('code')
            ->get();
            
        // Get already registered courses for current semester
        $registeredCourses = CourseRegistration::where('student_id', $student->id)
            ->where('semester_id', $currentSemester->id)
            ->with(['course'])
            ->get();
            
        $registeredCourseIds = $registeredCourses->pluck('course_id')->toArray();
        
        // Calculate current total units
        $totalUnits = $registeredCourses->sum(function($reg) {
            return $reg->course->credit_units ?? 0;
        });
        
        return view('student.course-registration', compact(
            'authUser',
            'student',
            'availableCourses',
            'registeredCourses',
            'registeredCourseIds',
            'currentSemester',
            'canRegister',
            'totalUnits'
        ));
    }
    
    /**
     * Store course registration
     */
    public function storeCourseRegistration(Request $request): RedirectResponse
    {
        $request->validate([
            'courses' => 'required|array|min:1',
            'courses.*' => 'exists:courses,id'
        ]);
        
        $authUser = Auth::user();
        $student = Student::where('user_id', $authUser->id)->first();
        
        if (!$student) {
            return redirect()->route('student.course-registration.current')
                ->with('error', 'Student profile not found.');
        }
        
        // Get current semester
        $currentSemester = Semester::where('is_current', true)->first();
        
        if (!$currentSemester) {
            return redirect()->route('student.course-registration.current')
                ->with('error', 'No active semester found.');
        }
        
        // Check for registration deadline
        if ($currentSemester->registration_end_date && now() > $currentSemester->registration_end_date) {
            return redirect()->route('student.course-registration.current')
                ->with('error', 'Course registration has closed for this semester.');
        }
        
        // Check fee payment status
        $hasPaidFees = $this->checkFeePayment($student);
        if (!$hasPaidFees) {
            return redirect()->route('student.course-registration.current')
                ->with('error', 'You must pay your school fees to register for courses.');
        }
        
        // Validate selected courses belong to student's programme and level
        $validCourses = Course::whereIn('id', $request->courses)
            ->where('programme_id', $student->programme_id)
            ->when($student->level_id, function($query) use ($student) {
                $query->where('level_id', $student->level_id);
            })
            ->where('semester_id', $currentSemester->id)
            ->get();
            
        if ($validCourses->count() !== count($request->courses)) {
            return redirect()->route('student.course-registration.current')
                ->with('error', 'Some selected courses are not valid for your programme or level.');
        }
        
        // Calculate total units
        $totalUnits = $validCourses->sum('credit_units');
        
        // Optional: Validate unit limits (with warnings, not hard stops)
        $warningMessage = null;
        if ($totalUnits > 24) {
            $warningMessage = 'You have selected ' . $totalUnits . ' units which exceeds the recommended maximum of 24 units.';
        } elseif ($totalUnits < 15) {
            $warningMessage = 'You have selected ' . $totalUnits . ' units which is below the recommended minimum of 15 units.';
        }
        
        try {
            DB::transaction(function() use ($request, $student, $currentSemester) {
                // Remove existing registrations for this semester
                CourseRegistration::where('student_id', $student->id)
                    ->where('semester_id', $currentSemester->id)
                    ->delete();
                
                // Create new registrations
                $registrations = [];
                foreach ($request->courses as $courseId) {
                    $registrations[] = [
                        'student_id' => $student->id,
                        'course_id' => $courseId,
                        'school_session_id' => $currentSemester->school_session_id,
                        'semester_id' => $currentSemester->id,
                        'level_id' => $student->level_id,
                        'status' => 'approved', // Auto-approve or set to 'pending' based on your requirements
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                
                CourseRegistration::insert($registrations);
            });
            
            $successMessage = 'Course registration completed successfully! You have registered for ' . count($request->courses) . ' courses (' . $totalUnits . ' units).';
            
            if ($warningMessage) {
                return redirect()->route('student.course-registration.current')
                    ->with('success', $successMessage)
                    ->with('warning', $warningMessage);
            }
            
            return redirect()->route('student.course-registration.current')
                ->with('success', $successMessage);
                
        } catch (\Exception $e) {
            return redirect()->route('student.course-registration.current')
                ->with('error', 'An error occurred while processing your registration. Please try again.');
        }
    }
    
    /**
     * Check if student has paid fees for current semester
     */
    private function checkFeePayment(Student $student): bool
    {
        $currentSession = SchoolSession::where('is_current', true)->first();
        $currentSemester = Semester::where('is_current', true)->first();

        if (!$currentSession || !$currentSemester) {
            return true; // No current session/semester defined, allow registration
        }

        // Check if a fee record exists for this student, session, and semester
        $requiredFee = SchoolFee::where('programme_id', $student->programme_id)
            ->where('school_session_id', $currentSession->id)
            ->where('semester_id', $currentSemester->id)
            ->when($student->level_id, function($query) use ($student) {
                $query->where('level_id', $student->level_id);
            })
            ->where('is_active', true)
            ->first();

        if (!$requiredFee) {
            return true; // No fee defined, so student can proceed
        }

        $totalPaid = SchoolFeePayment::where('student_id', $student->id)
            ->where('school_fee_id', $requiredFee->id)
            ->where('status', 'successful')
            ->sum('amount');

        return $totalPaid >= $requiredFee->amount;
    }
    
    /**
     * Get outstanding fees for student
     */
    private function getOutstandingFees(Student $student): float
    {
        $currentSession = SchoolSession::where('is_current', true)->first();
        $currentSemester = Semester::where('is_current', true)->first();
        
        if (!$currentSession || !$currentSemester) {
            return 0;
        }
        
        $activeFees = SchoolFee::where('programme_id', $student->programme_id)
            ->where('school_session_id', $currentSession->id)
            ->where('semester_id', $currentSemester->id)
            ->when($student->level_id, function($query) use ($student) {
                $query->where('level_id', $student->level_id);
            })
            ->where('is_active', true)
            ->get();
            
        $totalOutstanding = 0;
        foreach ($activeFees as $fee) {
            $totalPaid = SchoolFeePayment::where('student_id', $student->id)
                ->where('school_fee_id', $fee->id)
                ->where('status', 'successful')
                ->sum('amount');
                
            $outstanding = $fee->amount - $totalPaid;
            if ($outstanding > 0) {
                $totalOutstanding += $outstanding;
            }
        }
        
        return $totalOutstanding;
    }
    
    /**
     * Display course registration history
     */
    public function courseRegistrationHistory(): View
    {
        $authUser = Auth::user();
        $student = Student::with('user')->where('user_id', $authUser->id)->first();
        
        if (!$student) {
            return view('student.course-registration-history')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'authUser' => $authUser,
                'student' => null,
                'registrationHistory' => [],
                'totalSessions' => 0,
                'totalCourses' => 0,
                'totalUnits' => 0,
                'averageUnitsPerSemester' => 0
            ]);
        }
        
        // Get registration history grouped by session and semester
        $registrations = CourseRegistration::where('student_id', $student->id)
            ->with(['course', 'semester.schoolSession', 'level'])
            ->join('semesters', 'course_registrations.semester_id', '=', 'semesters.id')
            ->join('school_sessions', 'semesters.school_session_id', '=', 'school_sessions.id')
            ->orderBy('school_sessions.session_name', 'desc')
            ->orderBy('semesters.semester_name', 'asc')
            ->select('course_registrations.*')
            ->get();
            
        // Group by session and semester
        $registrationHistory = [];
        $totalCourses = 0;
        $totalUnits = 0;
        $semesterCount = 0;
        
        foreach ($registrations->groupBy(function($item) {
            return $item->semester->schoolSession->session_name ?? 'Unknown Session';
        }) as $sessionName => $sessionRegistrations) {
            
            $registrationHistory[$sessionName] = [];
            
            foreach ($sessionRegistrations->groupBy('semester.semester_name') as $semesterName => $semesterRegistrations) {
                $courses = [];
                $semesterUnits = 0;
                $semesterCount++;
                
                foreach ($semesterRegistrations as $registration) {
                    $courseUnits = $registration->course->credit_units ?? 0;
                    
                    $courses[] = [
                        'code' => $registration->course->code,
                        'title' => $registration->course->title,
                        'units' => $courseUnits,
                        'type' => $registration->course->course_type ?? 'Elective',
                        'status' => $registration->status ?? 'approved'
                    ];
                    
                    $semesterUnits += $courseUnits;
                    $totalCourses++;
                }
                
                $totalUnits += $semesterUnits;
                
                $registrationHistory[$sessionName][$semesterName] = [
                    'courses' => $courses,
                    'total_units' => $semesterUnits,
                    'course_count' => count($courses),
                    'registration_date' => $semesterRegistrations->first()->created_at->format('M j, Y'),
                    'status' => $semesterRegistrations->first()->status ?? 'approved',
                    'level' => $semesterRegistrations->first()->level->name ?? 'N/A',
                    'semester_id' => $semesterRegistrations->first()->semester_id
                ];
            }
        }
        
        $totalSessions = count($registrationHistory);
        $averageUnitsPerSemester = $semesterCount > 0 ? round($totalUnits / $semesterCount, 1) : 0;
            
        return view('student.course-registration-history', compact(
            'authUser', 
            'student', 
            'registrationHistory',
            'totalSessions',
            'totalCourses', 
            'totalUnits',
            'averageUnitsPerSemester'
        ));
    }
    
    /**
     * Display fee payments
     */
    public function feePayments(): View
    {
        $authUser = Auth::user();
        $student = Student::with('user', 'programme')->where('user_id', $authUser->id)->first();
        
        if (!$student) {
            return view('student.fee-payments')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'authUser' => $authUser,
                'student' => null,
                'outstandingFees' => collect(),
                'recentPayments' => collect(),
                'totalOutstanding' => 0,
                'totalPaid' => 0,
                'paymentProgress' => 0
            ]);
        }
        
        // Get current session and semester
        $currentSession = SchoolSession::where('is_current', true)->first();
        $currentSemester = Semester::where('is_current', true)->first();
        
        // Get active school fees for the student's programme, current session, semester, and level
        $activeFees = SchoolFee::where('programme_id', $student->programme_id) 
            ->where('school_session_id', $currentSession?->id)
            ->where('semester_id', $currentSemester?->id)
            ->when($student->level_id, function($query) use ($student) {
                $query->where('level_id', $student->level_id);
            })
            ->where('is_active', true)
            ->with(['programme', 'schoolSession', 'semester', 'level'])
            ->get();

        // Filter out fees that have been fully paid
        $outstandingFees = $activeFees->filter(function($fee) use ($student) {
            $totalPaid = SchoolFeePayment::where('student_id', $student->id)
                ->where('school_fee_id', $fee->id)
                ->where('status', 'successful')
                ->sum('amount');
            
            return $totalPaid < $fee->amount;
        });
        
        // Add remaining amount to each outstanding fee
        $outstandingFees = $outstandingFees->map(function($fee) use ($student) {
            $totalPaid = SchoolFeePayment::where('student_id', $student->id)
                ->where('school_fee_id', $fee->id)
                ->where('status', 'successful')
                ->sum('amount');
            
            $fee->remaining_amount = $fee->amount - $totalPaid;
            $fee->paid_amount = $totalPaid;
            return $fee;
        });
            
        $recentPayments = SchoolFeePayment::where('student_id', $student->id)
            ->with('schoolFee')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Calculate payment summary
        $totalOutstanding = $outstandingFees->sum('remaining_amount');
        $totalPaid = SchoolFeePayment::where('student_id', $student->id)
            ->where('status', 'successful')
            ->sum('amount');
        
        $totalFees = $activeFees->sum('amount');
        $paymentProgress = $totalFees > 0 ? round(($totalPaid / $totalFees) * 100, 1) : 0;
            
        return view('student.fee-payments', compact('authUser', 'student', 'outstandingFees', 'recentPayments', 'totalOutstanding', 'totalPaid', 'paymentProgress'));
    }
    
    /**
     * Process fee payment (Legacy method - now handled by PaymentController)
     * This method is kept for backward compatibility but redirects to the new payment flow
     */
    public function processPayment(Request $request): RedirectResponse
    {
        // Redirect to the new payment initialization route
        return redirect()->route('payment.initialize')
            ->with('error', 'Please use the Pay Now button to process payments.');
    }
    
    /**
     * Display payment history
     */
    public function paymentHistory(): View
    {
        $authUser = Auth::user();
        $student = Student::with('user')->where('user_id', $authUser->id)->first();
        
        if (!$student) {
            return view('student.payment-history')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'authUser' => $authUser,
                'student' => null,
                'payments' => collect()->paginate(15),
                'totalPaid' => 0,
                'totalPayments' => 0,
                'successfulPayments' => 0,
                'pendingPayments' => 0,
                'averagePayment' => 0,
                'lastPaymentDate' => null,
                'availableSessions' => collect()
            ]);
        }
        
        $payments = SchoolFeePayment::where('student_id', $student->id)
            ->with(['schoolFee.programme', 'schoolFee.semester', 'schoolFee.schoolSession'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        // Calculate payment statistics
        $totalPaid = SchoolFeePayment::where('student_id', $student->id)
            ->where('status', 'successful')
            ->sum('amount');
            
        $totalPayments = SchoolFeePayment::where('student_id', $student->id)
            ->count();
            
        $successfulPayments = SchoolFeePayment::where('student_id', $student->id)
            ->where('status', 'successful')
            ->count();
            
        $pendingPayments = SchoolFeePayment::where('student_id', $student->id)
            ->where('status', 'pending')
            ->count();
            
        $averagePayment = $successfulPayments > 0 ? $totalPaid / $successfulPayments : 0;
        
        $lastPaymentDate = SchoolFeePayment::where('student_id', $student->id)
            ->where('status', 'successful')
            ->latest('payment_date')
            ->value('payment_date');
            
        // Get available sessions for filter
        $availableSessions = \App\Models\SchoolSession::orderBy('session_name', 'desc')->get();
            
        return view('student.payment-history', compact(
            'authUser', 
            'student', 
            'payments', 
            'totalPaid', 
            'totalPayments', 
            'successfulPayments',
            'pendingPayments',
            'averagePayment', 
            'lastPaymentDate',
            'availableSessions'
        ));
    }
    
    /**
     * Generate payment receipt
     */
    public function paymentReceipt($paymentId): View
    {
        $authUser = Auth::user();
        $student = Student::where('user_id', $authUser->id)->first();
        
        if (!$student) {
            return view('student.payment-receipt')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'authUser' => $authUser,
                'student' => null,
                'payment' => null
            ]);
        }
        
        $payment = SchoolFeePayment::where('id', $paymentId)
            ->where('student_id', $student->id)
            ->with(['schoolFee.programme', 'schoolFee.semester', 'schoolFee.schoolSession'])
            ->firstOrFail();
            
        return view('student.payment-receipt', compact('authUser', 'student', 'payment'));
    }
    
    /**
     * Display academic results
     */
    public function results(): View
    {
        $authUser = Auth::user();
        $student = Student::where('user_id', $authUser->id)->first();
        
        if (!$student) {
            return view('student.results')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'authUser' => $authUser,
                'student' => null,
                'results' => collect(),
                'overallGPA' => 0
            ]);
        }
        
        $results = Result::where('student_id', $student->id)
            ->with(['course', 'semester.schoolSession'])
            ->join('semesters', 'results.semester_id', '=', 'semesters.id')
            ->join('school_sessions', 'semesters.school_session_id', '=', 'school_sessions.id')
            ->orderBy('school_sessions.session_name', 'desc')
            ->orderBy('semesters.semester_name', 'asc')
            ->select('results.*')
            ->get()
            ->groupBy(function($result) {
                return $result->semester->schoolSession->session_name . ' - ' . $result->semester->semester_name;
            });
            
        // Use the new GPA calculation method
        $overallGPA = $student->calculateGPA() ?? 0;
            
        return view('student.results', compact('authUser', 'student', 'results', 'overallGPA'));
    }
    
    /**
     * Display results by semester
     */
    public function resultsBySemester($semesterId): View
    {
        $authUser = Auth::user();
        $student = Student::where('user_id', $authUser->id)->first();
        
        if (!$student) {
            return view('student.semester-results')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'authUser' => $authUser,
                'student' => null,
                'results' => collect(),
                'semesterGPA' => 0,
                'semester' => null
            ]);
        }
        
        $semester = Semester::with('schoolSession')->findOrFail($semesterId);
        
        $results = Result::where('student_id', $student->id)
            ->where('semester_id', $semesterId)
            ->with(['course', 'semester.schoolSession'])
            ->orderBy('course_id')
            ->get();
            
        // Use the new GPA calculation method for specific semester
        $semesterGPA = $student->calculateGPA($semesterId) ?? 0;
        
        // Calculate semester statistics
        $totalUnits = $results->sum(function($result) {
            return $result->course->credit_units ?? 0;
        });
        
        $totalGradePoints = $results->sum(function($result) {
            return ($result->course->credit_units ?? 0) * ($result->grade_point ?? 0);
        });
        
        return view('student.semester-results', compact(
            'authUser', 
            'student', 
            'results', 
            'semesterGPA', 
            'semester',
            'totalUnits',
            'totalGradePoints'
        ));
    }
    
    /**
     * Display support page
     */
    public function support(): View
    {
        $authUser = Auth::user();
        $student = Student::where('user_id', $authUser->id)->first();
        
        if (!$student) {
            return view('student.support')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'authUser' => $authUser,
                'student' => null
            ]);
        }
        
        // Get common support topics/FAQs
        $supportTopics = [
            [
                'title' => 'Course Registration Issues',
                'description' => 'Problems with course selection, registration deadlines, or course availability',
                'icon' => 'fas fa-book',
                'category' => 'academic'
            ],
            [
                'title' => 'Fee Payment Problems',
                'description' => 'Issues with payment processing, receipt generation, or fee calculations',
                'icon' => 'fas fa-credit-card',
                'category' => 'financial'
            ],
            [
                'title' => 'Academic Records',
                'description' => 'Questions about grades, transcripts, or academic standing',
                'icon' => 'fas fa-chart-line',
                'category' => 'academic'
            ],
            [
                'title' => 'Profile Updates',
                'description' => 'Help with updating personal information, photos, or contact details',
                'icon' => 'fas fa-user-edit',
                'category' => 'profile'
            ],
            [
                'title' => 'Technical Support',
                'description' => 'Login issues, system errors, or general technical problems',
                'icon' => 'fas fa-cog',
                'category' => 'technical'
            ]
        ];
        
        return view('student.support', compact('authUser', 'student', 'supportTopics'));
    }
    
    /**
     * Get student's current semester registration status
     */
    public function getRegistrationStatus(): array
    {
        $authUser = Auth::user();
        $student = Student::where('user_id', $authUser->id)->first();
        
        if (!$student) {
            return [
                'can_register' => false,
                'message' => 'Student profile not found.',
                'registered_courses' => 0,
                'total_units' => 0
            ];
        }
        
        $currentSemester = Semester::where('is_current', true)->first();
        
        if (!$currentSemester) {
            return [
                'can_register' => false,
                'message' => 'No active semester found.',
                'registered_courses' => 0,
                'total_units' => 0
            ];
        }
        
        // Check registration deadline
        $canRegister = true;
        $message = 'Registration is open.';
        
        if ($currentSemester->registration_end_date && now() > $currentSemester->registration_end_date) {
            $canRegister = false;
            $message = 'Course registration has closed for this semester.';
        }
        
        // Check fee payment
        if ($canRegister && !$this->checkFeePayment($student)) {
            $canRegister = false;
            $message = 'You must pay your school fees to register for courses.';
        }
        
        // Get current registration
        $registeredCourses = CourseRegistration::where('student_id', $student->id)
            ->where('semester_id', $currentSemester->id)
            ->with('course')
            ->get();
            
        $totalUnits = $registeredCourses->sum(function($reg) {
            return $reg->course->credit_units ?? 0;
        });
        
        return [
            'can_register' => $canRegister,
            'message' => $message,
            'registered_courses' => $registeredCourses->count(),
            'total_units' => $totalUnits,
            'semester' => $currentSemester->semester_name . ' ' . ($currentSemester->schoolSession->session_name ?? ''),
            'deadline' => $currentSemester->registration_end_date ? 
                $currentSemester->registration_end_date->format('F j, Y') : null
        ];
    }
    
    /**
     * Get available courses for student
     */
    public function getAvailableCourses(): array
    {
        $authUser = Auth::user();
        $student = Student::where('user_id', $authUser->id)->first();
        
        if (!$student) {
            return ['error' => 'Student profile not found.'];
        }
        
        $currentSemester = Semester::where('is_current', true)->first();
        
        if (!$currentSemester) {
            return ['error' => 'No active semester found.'];
        }
        
        $availableCourses = Course::where('programme_id', $student->programme_id)
            ->when($student->level_id, function($query) use ($student) {
                $query->where('level_id', $student->level_id);
            })
            ->where('semester_id', $currentSemester->id)
            ->with(['level', 'semester'])
            ->orderBy('code')
            ->get()
            ->map(function($course) {
                return [
                    'id' => $course->id,
                    'code' => $course->code,
                    'title' => $course->title,
                    'units' => $course->credit_units,
                    'type' => $course->course_type ?? 'Elective'
                ];
            });
            
        return ['courses' => $availableCourses];
    }
    
    /**
     * Helper method to calculate grade point from score
     */
    private function calculateGradePoint($score): float
    {
        if ($score >= 70) return 5.0;
        if ($score >= 60) return 4.0;
        if ($score >= 50) return 3.0;
        if ($score >= 45) return 2.0;
        if ($score >= 40) return 1.0;
        return 0.0;
    }
    
    /**
     * Helper method to get letter grade from score
     */
    private function getLetterGrade($score): string
    {
        if ($score >= 70) return 'A';
        if ($score >= 60) return 'B';
        if ($score >= 50) return 'C';
        if ($score >= 45) return 'D';
        if ($score >= 40) return 'E';
        return 'F';
    }
    
    /**
     * Validate student access to resource
     */
    private function validateStudentAccess($resourceStudentId): bool
    {
        $authUser = Auth::user();
        $student = Student::where('user_id', $authUser->id)->first();
        
        return $student && $student->id == $resourceStudentId;
    }
}