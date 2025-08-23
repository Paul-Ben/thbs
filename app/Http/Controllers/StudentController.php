<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\SchoolFeePayment;
use App\Models\Result;
use App\Models\SchoolFee;
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
        // $student = $authUser->student;
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
            
        $currentCGPA = $student->results()->avg('score') ?? 0;
        
        // $outstandingFees = SchoolFee::where('student_id', $student->id)
        //     ->where('status', 'pending')
        //     ->sum('amount');
            
        $currentLevel = $student->current_level ?? '100 Level';
        
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
                            'status' => 'completed'
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
            // 'outstandingFees',
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
        
        return redirect()->route('student.biodata')
            ->with('success', 'Biodata updated successfully!');
    }
    
    /**
     * Update student photo
     */
    public function updatePhoto(Request $request): JsonResponse
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);
        
        $student = Auth::user()->student;
        
        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student profile not found. Please contact the administration to complete your student registration.'
            ], 404);
        }
        
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($student->photo && Storage::exists('public/student-photos/' . $student->photo)) {
                Storage::delete('public/student-photos/' . $student->photo);
            }
            
            // Store new photo
            $photoName = time() . '_' . $student->matric_number . '.' . $request->photo->extension();
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
    }
    
    /**
     * Display current course registration
     */
    public function courseRegistration(): View
    {
        $authUser = Auth::user();
        $student = $authUser->student;
        
        if (!$student) {
            return view('student.course-registration')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'authUser' => $authUser,
                'student' => null,
                'availableCourses' => collect(),
                'registeredCourses' => collect(),
                'registeredCourseIds' => []
            ]);
        }
        
        // Get available courses for current level and semester
        $availableCourses = Course::where('programme_id', $student->programme_id)
            ->whereHas('level', function($query) use ($student) {
                $query->where('id', $student->level_id ?? 'Level not set');
            })
            ->whereHas('semester', function($query) {
                $query->where('is_current', true);
            })
            ->with(['level', 'semester'])
            ->get();
            
        // Get already registered courses
        $registeredCourses = CourseRegistration::where('student_id', $student->id)
            ->whereHas('semester', function($query) {
                $query->where('is_current', true);
            })
            ->with('course')
            ->get();
            
        $registeredCourseIds = $registeredCourses->pluck('course_id')->toArray();
        
        return view('student.course-registration', compact(
            'authUser',
            'student',
            'availableCourses',
            'registeredCourses',
            'registeredCourseIds'
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
        
        $student = Auth::user()->student;
        
        if (!$student) {
            return redirect()->route('student.course-registration')
                ->with('error', 'Student profile not found. Please contact the administration to complete your student registration.');
        }
        
        DB::transaction(function() use ($request, $student) {
            // Delete existing registrations for current semester
            CourseRegistration::where('student_id', $student->id)
                ->whereHas('semester', function($query) {
                    $query->where('is_current', true);
                })
                ->delete();
                
            // Register new courses
            foreach ($request->courses as $courseId) {
                $course = Course::find($courseId);
                CourseRegistration::create([
                    'student_id' => $student->id,
                    'course_id' => $courseId,
                    'semester_id' => $course->semester_id,
                    'level_id' => $course->level_id,
                    'status' => 'registered'
                ]);
            }
        });
        
        return redirect()->route('student.course-registration')
            ->with('success', 'Course registration completed successfully!');
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
                'registrations' => collect()->paginate(15)
            ]);
        }
        
        $registrations = CourseRegistration::where('student_id', $student->id)
            ->with(['course', 'semester', 'level'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('student.course-registration-history', compact('authUser', 'student', 'registrations'));
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
                'recentPayments' => collect()
            ]);
        }
        
        // Get current session and semester
        $currentSession = \App\Models\SchoolSession::where('is_current', true)->first();
        $currentSemester = \App\Models\Semester::where('is_current', true)->first();
        
        // Get active school fees for the student's programme, current session, semester, and level
        $activeFees = SchoolFee::where('programme_id', $student->programme->id ) 
            ->where('school_session_id', $currentSession?->id)
            ->where('semester_id', $currentSemester?->id)
            ->where('level_id', $student->level_id)
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
            ->with('schoolFee')
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
        $student = $authUser->student;
        
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
            ->with('schoolFee')
            ->firstOrFail();
            
        return view('student.payment-receipt', compact('authUser', 'student', 'payment'));
    }
    
    /**
     * Display academic results
     */
    public function results(): View
    {
        $authUser = Auth::user();
        $student = $authUser->student;
        
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
            ->with(['course', 'semester'])
            ->orderBy('semester_id', 'desc')
            ->get()
            ->groupBy('semester.semester_name');
            
        $overallGPA = Result::where('student_id', $student->id)
            ->avg('grade_point') ?? 0;
            
        return view('student.results', compact('authUser', 'student', 'results', 'overallGPA'));
    }
    
    /**
     * Display results by semester
     */
    public function resultsBySemester($semesterId): View
    {
        $authUser = Auth::user();
        $student = $authUser->student;
        
        if (!$student) {
            return view('student.semester-results')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'authUser' => $authUser,
                'student' => null,
                'results' => collect(),
                'semesterGPA' => 0
            ]);
        }
        
        $results = Result::where('student_id', $student->id)
            ->where('semester_id', $semesterId)
            ->with(['course', 'semester'])
            ->get();
            
        $semesterGPA = $results->avg('grade_point') ?? 0;
        
        return view('student.semester-results', compact('authUser', 'student', 'results', 'semesterGPA'));
    }
    
    /**
     * Display support page
     */
    public function support(): View
    {
        $authUser = Auth::user();
        $student = $authUser->student;
        
        if (!$student) {
            return view('student.support')->with([
                'error' => 'Student profile not found. Please contact the administration to complete your student registration.',
                'authUser' => $authUser,
                'student' => null
            ]);
        }
        
        return view('student.support', compact('authUser', 'student'));
    }
}