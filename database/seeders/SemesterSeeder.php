<?php

namespace Database\Seeders;

use App\Models\SchoolSession;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // recent school session
        $schoolSession = SchoolSession::orderBy('year', 'desc')->first();

        // Check if a school session exists
        if ($schoolSession) {
         
            Semester::create([
                'semester_name' => 'First Semester',
                'school_session_id' => $schoolSession->id,
                'is_current' => true, 
            ]);

      
            Semester::create([
                'semester_name' => 'Second Semester',
                'school_session_id' => $schoolSession->id,
            ]);
        }
    }
}