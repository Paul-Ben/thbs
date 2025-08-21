<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Programme;
use App\Models\Semester;
use App\Models\Level;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
      
        $nursingProgramme = Programme::where('name', 'B.N.Sc Nursing Science')->first();
        $medicineProgramme = Programme::where('name', 'MB.BS Medicine and Surgery')->first();

  
        $firstSemester = Semester::where('semester_name', 'First Semester')->first();
        $secondSemester = Semester::where('semester_name', 'Second Semester')->first();
   
        if (!$nursingProgramme || !$medicineProgramme || !$firstSemester || !$secondSemester) {
            $this->command->warn('Required programmes or semesters not found. Skipping CourseSeeder.');
            return;
        }

      
        $findLevel = function (string $levelName, int $semesterId) {
            return Level::where('name', $levelName)
                        ->where('semester_id', $semesterId)
                        ->first();
        };

        // 100 Level courses for First Semester
        $level100_firstSem = $findLevel('100 Level', $firstSemester->id);
        if ($level100_firstSem) {
            Course::create([
                'code' => 'NUR101',
                'title' => 'Introduction to Nursing',
                'programme_id' => $nursingProgramme->id,
                'semester_id' => $firstSemester->id,
                'level_id' => $level100_firstSem->id,
            ]);

            Course::create([
                'code' => 'MED101',
                'title' => 'Human Anatomy I',
                'programme_id' => $medicineProgramme->id,
                'semester_id' => $firstSemester->id,
                'level_id' => $level100_firstSem->id,
            ]);
        }

        // 100 Level courses for Second Semester
        $level100_secondSem = $findLevel('100 Level', $secondSemester->id);
        if ($level100_secondSem) {
            Course::create([
                'code' => 'NUR102',
                'title' => 'Fundamentals of Nursing Practice',
                'programme_id' => $nursingProgramme->id,
                'semester_id' => $secondSemester->id,
                'level_id' => $level100_secondSem->id,
            ]);

            Course::create([
                'code' => 'MED102',
                'title' => 'Human Physiology I',
                'programme_id' => $medicineProgramme->id,
                'semester_id' => $secondSemester->id,
                'level_id' => $level100_secondSem->id,
            ]);
        }

        // 200 Level courses for First Semester
        $level200_firstSem = $findLevel('200 Level', $firstSemester->id);
        if ($level200_firstSem) {
            Course::create([
                'code' => 'NUR201',
                'title' => 'Medical-Surgical Nursing',
                'programme_id' => $nursingProgramme->id,
                'semester_id' => $firstSemester->id,
                'level_id' => $level200_firstSem->id,
            ]);

            Course::create([
                'code' => 'MED201',
                'title' => 'Medical Biochemistry',
                'programme_id' => $medicineProgramme->id,
                'semester_id' => $firstSemester->id,
                'level_id' => $level200_firstSem->id,
            ]);
        }
    }
}