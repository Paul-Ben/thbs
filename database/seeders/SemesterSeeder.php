<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Semester;
use App\Models\SchoolSession;
use Carbon\Carbon;

class SemesterSeeder extends Seeder
{
    public function run(): void
    {
        $currentSession = SchoolSession::where('is_current', true)->first();
        if (!$currentSession) {
            $currentSession = SchoolSession::first();
        }

        if (!$currentSession) {
            $this->command->warn('No school sessions found. Run SchoolSession seeder first.');
            return;
        }

        $semesters = [
            [
                'semester_name' => 'First Semester',
                'school_session_id' => $currentSession->id,
                'is_current' => true,
                'registration_start_date' => Carbon::now()->subWeeks(2)->toDateString(),
                'registration_end_date' => Carbon::now()->addWeeks(2)->toDateString(),
            ],
            [
                'semester_name' => 'Second Semester',
                'school_session_id' => $currentSession->id,
                'is_current' => false,
                'registration_start_date' => Carbon::now()->addMonths(4)->toDateString(),
                'registration_end_date' => Carbon::now()->addMonths(5)->toDateString(),
            ],
        ];

        foreach ($semesters as $data) {
            Semester::firstOrCreate(
                [
                    'semester_name' => $data['semester_name'],
                    'school_session_id' => $data['school_session_id'],
                ],
                [
                    'is_current' => $data['is_current'],
                    'registration_start_date' => $data['registration_start_date'],
                    'registration_end_date' => $data['registration_end_date'],
                ]
            );
        }
    }
}
