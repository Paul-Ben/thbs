<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Programme;
use App\Models\ApplicationSession;
use App\Models\AcademicRecord;
use App\Models\ApplicationFeePayment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting ApplicationSeeder...');
        
        // Validate required data exists
        $this->validateRequiredData();
        
        // Create test applications
        $this->createTestApplications();
        
        $this->command->info('ApplicationSeeder completed successfully!');
    }
    
    /**
     * Validate that required data exists
     */
    private function validateRequiredData(): void
    {
        $programmes = Programme::all();
        $applicationSessions = ApplicationSession::all();
        
        if ($programmes->isEmpty()) {
            throw new \Exception('No programmes found. Please run ProgrammeSeeder first.');
        }
        
        if ($applicationSessions->isEmpty()) {
            throw new \Exception('No application sessions found. Please run ApplicationSessionSeeder first.');
        }
        
        $this->command->info('Required data validated successfully.');
    }
    
    /**
     * Create test applications
     */
    private function createTestApplications(): void
    {
        $currentSession = ApplicationSession::where('is_current', true)->first() 
                         ?? ApplicationSession::latest('year')->first();
        
        $programmes = Programme::all();
        
        // Application data that matches the StudentUserSeeder
        $applications = [
            [
                'payment_reference' => 'PAY_' . time() . '_001',
                'application_session_id' => $currentSession->id,
                'application_number' => 'THBS/APP/2024/001',
                'applicant_surname' => 'Doe',
                'applicant_othernames' => 'John',
                'date_of_birth' => '2000-05-15',
                'gender' => 'Male',
                'state_of_origin' => 'Lagos',
                'lga' => 'Ikeja',
                'nationality' => 'Nigerian',
                'religion' => 'Christianity',
                'marital_status' => 'Single',
                'home_town' => 'Lagos',
                'email' => 'john.doe.applicant@example.com',
                'phone' => '+2348123456789',
                'correspondence_address' => '123 University Road, Lagos State',
                'employment_status' => 'Student',
                'permanent_home_address' => '123 University Road, Lagos State',
                'parent_guardian_name' => 'Mr. James Doe',
                'parent_guardian_phone' => '+2348123456788',
                'parent_guardian_address' => '123 University Road, Lagos State',
                'parent_guardian_occupation' => 'Engineer',
                'programme_id' => $programmes->where('name', 'LIKE', '%Nursing%')->first()?->id ?? $programmes->first()->id,
                'status' => 'approved',
                'passport' => 'applicant_passport_001.jpg',
                'credential' => 'applicant_credentials_001.pdf',
                'declaration_check' => true,
            ],
            [
                'payment_reference' => 'PAY_' . time() . '_002',
                'application_session_id' => $currentSession->id,
                'application_number' => 'THBS/APP/2024/002',
                'applicant_surname' => 'Smith',
                'applicant_othernames' => 'Jane Mary',
                'date_of_birth' => '1999-08-22',
                'gender' => 'Female',
                'state_of_origin' => 'Ogun',
                'lga' => 'Abeokuta South',
                'nationality' => 'Nigerian',
                'religion' => 'Christianity',
                'marital_status' => 'Single',
                'home_town' => 'Abeokuta',
                'email' => 'jane.smith.applicant@example.com',
                'phone' => '+2348123456790',
                'correspondence_address' => '456 College Avenue, Ogun State',
                'employment_status' => 'Student',
                'permanent_home_address' => '456 College Avenue, Ogun State',
                'parent_guardian_name' => 'Mrs. Mary Smith',
                'parent_guardian_phone' => '+2348123456791',
                'parent_guardian_address' => '456 College Avenue, Ogun State',
                'parent_guardian_occupation' => 'Teacher',
                'programme_id' => $programmes->where('name', 'LIKE', '%Computer%')->first()?->id ?? $programmes->skip(1)->first()?->id ?? $programmes->first()->id,
                'status' => 'pending',
                'passport' => 'applicant_passport_002.jpg',
                'credential' => 'applicant_credentials_002.pdf',
                'declaration_check' => true,
            ],
            [
                'payment_reference' => 'PAY_' . time() . '_003',
                'application_session_id' => $currentSession->id,
                'application_number' => 'THBS/APP/2024/003',
                'applicant_surname' => 'Johnson',
                'applicant_othernames' => 'Michael David',
                'date_of_birth' => '2001-03-10',
                'gender' => 'Male',
                'state_of_origin' => 'Kano',
                'lga' => 'Kano Municipal',
                'nationality' => 'Nigerian',
                'religion' => 'Islam',
                'marital_status' => 'Single',
                'home_town' => 'Kano',
                'email' => 'michael.johnson.applicant@example.com',
                'phone' => '+2348123456792',
                'correspondence_address' => '789 Education Close, Kano State',
                'employment_status' => 'Student',
                'permanent_home_address' => '789 Education Close, Kano State',
                'parent_guardian_name' => 'Alhaji Musa Johnson',
                'parent_guardian_phone' => '+2348123456793',
                'parent_guardian_address' => '789 Education Close, Kano State',
                'parent_guardian_occupation' => 'Businessman',
                'programme_id' => $programmes->where('name', 'LIKE', '%Business%')->first()?->id ?? $programmes->skip(2)->first()?->id ?? $programmes->first()->id,
                'status' => 'approved',
                'passport' => 'applicant_passport_003.jpg',
                'credential' => 'applicant_credentials_003.pdf',
                'declaration_check' => true,
            ],
            [
                'payment_reference' => 'PAY_' . time() . '_004',
                'application_session_id' => $currentSession->id,
                'application_number' => 'THBS/APP/2024/004',
                'applicant_surname' => 'Williams',
                'applicant_othernames' => 'Sarah Grace',
                'date_of_birth' => '2000-11-18',
                'gender' => 'Female',
                'state_of_origin' => 'Rivers',
                'lga' => 'Port Harcourt',
                'nationality' => 'Nigerian',
                'religion' => 'Christianity',
                'marital_status' => 'Single',
                'home_town' => 'Port Harcourt',
                'email' => 'sarah.williams.applicant@example.com',
                'phone' => '+2348123456794',
                'correspondence_address' => '321 Academic Road, Rivers State',
                'employment_status' => 'Student',
                'permanent_home_address' => '321 Academic Road, Rivers State',
                'parent_guardian_name' => 'Dr. Peter Williams',
                'parent_guardian_phone' => '+2348123456795',
                'parent_guardian_address' => '321 Academic Road, Rivers State',
                'parent_guardian_occupation' => 'Doctor',
                'programme_id' => $programmes->where('name', 'LIKE', '%Medicine%')->first()?->id ?? $programmes->skip(3)->first()?->id ?? $programmes->first()->id,
                'status' => 'pending',
                'passport' => 'applicant_passport_004.jpg',
                'credential' => 'applicant_credentials_004.pdf',
                'declaration_check' => true,
            ],
        ];
        
        foreach ($applications as $applicationData) {
            // Check if application already exists
            $existingApplication = Application::where('application_number', $applicationData['application_number'])->first();
            
            if ($existingApplication) {
                $this->command->warn("Application {$applicationData['application_number']} already exists. Skipping.");
                continue;
            }
            
            $application = Application::create($applicationData);
            
            // Create academic records for the application
            $this->createAcademicRecords($application);
            
            // Create successful payment record for approved applications
            if ($application->status === 'approved') {
                $this->createApplicationFeePayment($application);
            }
            
            $this->command->info("Created application: {$application->application_number} for {$application->applicant_surname}, {$application->applicant_othernames}");
        }
    }
    
    /**
     * Create academic records for an application
     */
    private function createAcademicRecords(Application $application): void
    {
        $academicRecords = [
            [
                'application_id' => $application->id,
                'level' => 'Secondary',
                'school_name' => 'Unity Secondary School',
                'exam_type' => 'WAEC',
                'exam_year' => '2018',
                'subject' => 'English Language',
                'grade' => 'B3',
                'number_of_sittings' => 1,
            ],
            [
                'application_id' => $application->id,
                'level' => 'Secondary',
                'school_name' => 'Unity Secondary School',
                'exam_type' => 'WAEC',
                'exam_year' => '2018',
                'subject' => 'Mathematics',
                'grade' => 'B2',
                'number_of_sittings' => 1,
            ],
            [
                'application_id' => $application->id,
                'level' => 'Secondary',
                'school_name' => 'Unity Secondary School',
                'exam_type' => 'WAEC',
                'exam_year' => '2018',
                'subject' => 'Biology',
                'grade' => 'A1',
                'number_of_sittings' => 1,
            ],
            [
                'application_id' => $application->id,
                'level' => 'Tertiary',
                'school_name' => 'Federal Polytechnic',
                'exam_type' => 'ND',
                'exam_year' => '2020',
                'certificate_obtained' => 'National Diploma',
                'graduation_year' => '2020',
                'other_qualification' => 'Computer Science',
            ]
        ];
        
        foreach ($academicRecords as $recordData) {
            AcademicRecord::create($recordData);
        }
    }
    
    /**
     * Create application fee payment for approved applications
     */
    private function createApplicationFeePayment(Application $application): void
    {
        ApplicationFeePayment::create([
            'amount' => 5000.00,
            'currency' => 'NGN',
            'payment_method' => 'card',
            'transaction_id' => 'TXN_' . time() . '_' . $application->id,
            'reference' => $application->payment_reference,
            'status' => 'successful',
            'payment_date' => Carbon::now()->subDays(rand(1, 30)),
            'description' => 'Application fee payment for ' . $application->application_number,
            'metadata' => json_encode([
                'application_id' => $application->id,
                'applicant_name' => $application->applicant_surname . ', ' . $application->applicant_othernames,
                'programme' => $application->programme->name ?? 'Unknown Programme'
            ])
        ]);
    }
}