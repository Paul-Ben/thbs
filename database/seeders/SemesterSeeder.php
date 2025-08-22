<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Semester;
use App\Models\SchoolSession;


class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.

     */
    public function run(): void
    {
        // Ensure there is at least one school session
        $currentSession = SchoolSession::where('is_current', true)->first();
        if (!$currentSession) {
            $currentSession = SchoolSession::first();
        }

        if (!$currentSession) {
            $this->command->warn('No school sessions found. Run SchoolSession seeder first.');
            return;
        }

        $semesters = [
            ['semester_name' => 'First Semester',  'school_session_id' => $currentSession->id, 'is_current' => true],
            ['semester_name' => 'Second Semester', 'school_session_id' => $currentSession->id, 'is_current' => false],
        ];

        foreach ($semesters as $data) {
            Semester::firstOrCreate(
                [
                    'semester_name' => $data['semester_name'],
                    'school_session_id' => $data['school_session_id'],
                ],
                [
                    'is_current' => $data['is_current'],
                ]
            );
        }
    }
}

