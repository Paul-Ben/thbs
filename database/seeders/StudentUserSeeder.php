<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Student;
use App\Models\Programme;
use App\Models\SchoolSession;
use App\Models\Semester;
use App\Models\Level;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Result;
use App\Models\SchoolFee;
use App\Models\SchoolFeePayment;
use App\Models\Application;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class StudentUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting StudentUserSeeder...');
        
        // Check if required data exists
        $this->validateRequiredData();
        
        // Create student user account
        $user = $this->createStudentUser();
        
        // Create student record
        $student = $this->createStudentRecord($user);
        
        // Create course registrations
        $this->createCourseRegistrations($student);
        
        // Create academic results
        $this->createAcademicResults($student);
        
        // Create school fee records
        $this->createSchoolFeeRecords($student);
        
        $this->command->info('StudentUserSeeder completed successfully!');
        $this->command->info('Student login credentials:');
        $this->command->line('  Email: student@thbs.edu.ng');
        $this->command->line('  Password: password123');
        $this->command->line('  Matric Number: THBS/2024/001');
    }
    
    /**
     * Validate that required data exists
     */
    private function validateRequiredData(): void
    {
        // Validate required data exists
        $programme = Programme::first();
        $sessions = SchoolSession::all();
        $semesters = Semester::all();
        $levels = Level::all();
        $courses = Course::all();
        $applications = Application::all();

        if (!$programme || $sessions->isEmpty() || $semesters->isEmpty() || $levels->isEmpty() || $courses->isEmpty() || $applications->isEmpty()) {
            throw new \Exception('Required data not found. Please ensure programmes, sessions, semesters, levels, courses, and applications are seeded first.');
        }
        
        // Check if Student role exists
        $studentRole = Role::where('name', 'Student')->first();
        if (!$studentRole) {
            $this->command->error('Student role not found. Please run RoleAndPermissionSeeder first.');
            throw new \Exception('Missing Student role');
        }
        
        $this->command->info('All required data validated successfully.');
    }
    
    /**
     * Create student user account
     */
    private function createStudentUser(): User
    {
        // Check if student user already exists
        $existingUser = User::where('email', 'student@thbs.edu.ng')->first();
        if ($existingUser) {
            $this->command->warn('Student user already exists. Using existing user.');
            return $existingUser;
        }
        
        $user = User::create([
            'name' => 'John Doe Student',
            'email' => 'student@thbs.edu.ng',
            'password' => Hash::make('password123'),
            'userRole' => 'Student',
            'email_verified_at' => now(),
        ]);
        
        // Assign Student role
        $studentRole = Role::where('name', 'Student')->first();
        $user->assignRole($studentRole);
        
        $this->command->info('Student user created successfully.');
        return $user;
    }
    
    /**
     * Create student record
     */
    private function createStudentRecord(User $user): Student
    {
        // Check if student record already exists
        $existingStudent = Student::where('user_id', $user->id)->first();
        if ($existingStudent) {
            $this->command->warn('Student record already exists. Using existing record.');
            return $existingStudent;
        }
        
        // Get a programme (preferably Nursing)
        $programme = Programme::where('name', 'LIKE', '%Nursing%')->first() 
                    ?? Programme::first();
        
        // Get an existing application and level
        $application = Application::first();
        $level = Level::where('name', '100 Level')->first();
        
        if (!$application) {
            throw new \Exception('No applications found. Please seed applications first.');
        }
        
        if (!$level) {
            throw new \Exception('No 100 Level found. Please seed levels first.');
        }
        
        $student = Student::create([
            'applicant_name' => 'John Doe Student',
            'matric_number' => 'THBS/2024/001',
            'email' => 'student@thbs.edu.ng',
            'gender' => 'Male',
            'phone' => '+2348123456789',
            'address' => '123 University Road, Lagos State',
            'country' => 'Nigeria',
            'state_of_origin' => 'Lagos',
            'lga' => 'Ikeja',
            'programme_id' => $programme->id,
            'date_of_birth' => '2000-05-15',
            'passport' => 'student_passport.jpg',
            'credential' => 'student_credentials.pdf',
            'user_id' => $user->id,
            'application_id' => $application->id,
            'level_id' => $level->id,
        ]);
        
        $this->command->info("Student record created for programme: {$programme->name}");
        return $student;
    }
    
    /**
     * Create course registrations for the student
     */
    private function createCourseRegistrations(Student $student): void
    {
        $currentSession = SchoolSession::where('is_current', true)->first() 
                         ?? SchoolSession::latest('year')->first();
        
        $firstSemester = Semester::where('semester_name', 'First Semester')->first();
        $secondSemester = Semester::where('semester_name', 'Second Semester')->first();
        
        if (!$currentSession || !$firstSemester) {
            $this->command->warn('Required session/semester data not found. Skipping course registrations.');
            return;
        }
        
        // Get 100 Level for first semester
        $level100First = Level::where('name', '100 Level')
                             ->where('semester_id', $firstSemester->id)
                             ->first();
        
        if ($level100First) {
            // Get courses for this level and semester
            $courses = Course::where('programme_id', $student->programme_id)
                           ->where('semester_id', $firstSemester->id)
                           ->where('level_id', $level100First->id)
                           ->take(5) // Register for 5 courses
                           ->get();
            
            foreach ($courses as $course) {
                // Check if registration already exists
                $existingRegistration = CourseRegistration::where('student_id', $student->id)
                                                         ->where('course_id', $course->id)
                                                         ->where('school_session_id', $currentSession->id)
                                                         ->first();
                
                if (!$existingRegistration) {
                    CourseRegistration::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'school_session_id' => $currentSession->id,
                        'semester_id' => $firstSemester->id,
                        'level_id' => $level100First->id,
                    ]);
                    
                    $this->command->info("Registered for course: {$course->code} - {$course->title}");
                }
            }
        }
        
        // Register for second semester if available
        if ($secondSemester) {
            $level100Second = Level::where('name', '100 Level')
                                 ->where('semester_id', $secondSemester->id)
                                 ->first();
            
            if ($level100Second) {
                $secondSemCourses = Course::where('programme_id', $student->programme_id)
                                        ->where('semester_id', $secondSemester->id)
                                        ->where('level_id', $level100Second->id)
                                        ->take(5)
                                        ->get();
                
                foreach ($secondSemCourses as $course) {
                    $existingRegistration = CourseRegistration::where('student_id', $student->id)
                                                             ->where('course_id', $course->id)
                                                             ->where('school_session_id', $currentSession->id)
                                                             ->first();
                    
                    if (!$existingRegistration) {
                        CourseRegistration::create([
                            'student_id' => $student->id,
                            'course_id' => $course->id,
                            'school_session_id' => $currentSession->id,
                            'semester_id' => $secondSemester->id,
                            'level_id' => $level100Second->id,
                        ]);
                        
                        $this->command->info("Registered for course: {$course->code} - {$course->title}");
                    }
                }
            }
        }
    }
    
    /**
     * Create academic results for the student
     */
    private function createAcademicResults(Student $student): void
    {
        $registrations = CourseRegistration::where('student_id', $student->id)->get();
        
        if ($registrations->isEmpty()) {
            $this->command->warn('No course registrations found. Skipping results creation.');
            return;
        }
        
        foreach ($registrations as $registration) {
            // Check if result already exists
            $existingResult = Result::where('student_id', $student->id)
                                   ->where('course_id', $registration->course_id)
                                   ->where('school_session_id', $registration->school_session_id)
                                   ->first();
            
            if (!$existingResult) {
                // Generate random but realistic scores
                $score = rand(60, 95); // Good student scores
                $grade = $this->calculateGrade($score);
                $gradePoint = $this->calculateGradePoint($score);
                
                Result::create([
                    'student_id' => $student->id,
                    'course_id' => $registration->course_id,
                    'school_session_id' => $registration->school_session_id,
                    'semester_id' => $registration->semester_id,
                    'level_id' => $registration->level_id,
                    'score' => $score,
                    'grade' => $grade,
                ]);
                
                $course = Course::find($registration->course_id);
                $this->command->info("Created result for {$course->code}: {$score}% ({$grade})");
            }
        }
    }
    
    /**
     * Create school fee records for the student
     */
    private function createSchoolFeeRecords(Student $student): void
    {
        $currentSession = SchoolSession::where('is_current', true)->first() 
                         ?? SchoolSession::latest('year')->first();
        
        if (!$currentSession) {
            $this->command->warn('No current session found. Skipping school fee records.');
            return;
        }
        
        // Get school fees for the student's programme and current session
        $schoolFees = SchoolFee::where('programme_id', $student->programme_id)
                              ->where('school_session_id', $currentSession->id)
                              ->where('is_active', true)
                              ->take(3) // Create a few fee records
                              ->get();
        
        foreach ($schoolFees as $schoolFee) {
            // Check if payment record already exists
            $existingPayment = SchoolFeePayment::where('student_id', $student->id)
                                              ->where('school_fee_id', $schoolFee->id)
                                              ->first();
            
            if (!$existingPayment) {
                // Create a partial payment (50% paid)
                $amountPaid = $schoolFee->amount * 0.5;
                
                SchoolFeePayment::create([
                    'student_id' => $student->id,
                    'school_fee_id' => $schoolFee->id,
                    'amount' => $amountPaid,
                    'payment_method' => 'bank_transfer',
                    'payment_reference' => 'PAY' . time() . rand(1000, 9999),
                    'payment_date' => now()->subDays(rand(1, 30)),
                    'status' => 'successful',
                    'description' => 'Partial payment for ' . $schoolFee->name,
                ]);
                
                $this->command->info("Created payment record for {$schoolFee->name}: ₦{$amountPaid}");
            }
        }
    }
    
    /**
     * Calculate grade based on score
     */
    private function calculateGrade(int $score): string
    {
        if ($score >= 70) return 'A';
        if ($score >= 60) return 'B';
        if ($score >= 50) return 'C';
        if ($score >= 45) return 'D';
        if ($score >= 40) return 'E';
        return 'F';
    }
    
    /**
     * Calculate grade point based on score
     */
    private function calculateGradePoint(int $score): float
    {
        if ($score >= 70) return 5.0;
        if ($score >= 60) return 4.0;
        if ($score >= 50) return 3.0;
        if ($score >= 45) return 2.0;
        if ($score >= 40) return 1.0;
        return 0.0;
    }
}