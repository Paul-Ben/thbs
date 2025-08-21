<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;
use App\Models\Semester;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting LevelSeeder...');
        
        // Get existing semesters to create realistic relationships
        $semesters = Semester::all();

        // If no semesters exist, create some basic ones first
        if ($semesters->isEmpty()) {
            $this->command->warn('Please ensure semesters table has data before running this seeder.');
            return;
        }

        $this->command->info('Available semesters: ' . $semesters->pluck('semester_name')->implode(', '));

        // Sample academic levels for different semester types
        $levelData = [
            // For First Semester
            [
                'semester_name' => 'First Semester',
                'levels' => [
                    ['name' => '100 Level', 'description' => 'First year students - Foundation level'],
                    ['name' => '200 Level', 'description' => 'Second year students - Intermediate level'],
                    ['name' => '300 Level', 'description' => 'Third year students - Advanced level'],
                    ['name' => '400 Level', 'description' => 'Fourth year students - Final year level'],
                    ['name' => '500 Level', 'description' => 'Fifth year students - Professional level (for 5-year programs)'],
                ]
            ],
            // For Second Semester
            [
                'semester_name' => 'Second Semester',
                'levels' => [
                    ['name' => '100 Level', 'description' => 'First year students - Foundation level'],
                    ['name' => '200 Level', 'description' => 'Second year students - Intermediate level'],
                    ['name' => '300 Level', 'description' => 'Third year students - Advanced level'],
                    ['name' => '400 Level', 'description' => 'Fourth year students - Final year level'],
                    ['name' => '500 Level', 'description' => 'Fifth year students - Professional level (for 5-year programs)'],
                ]
            ],
        ];

        $levelsCreated = 0;

        foreach ($levelData as $semesterLevels) {
            
            $semester = $semesters->first(function ($sem) use ($semesterLevels) {
                return stripos($sem->semester_name, str_replace(' Semester', '', $semesterLevels['semester_name'])) !== false;
            });

           
            if (!$semester) {
                $semester = $semesters->where('semester_name', $semesterLevels['semester_name'])->first();
            }

         
            if (!$semester) {
                $this->command->warn("Semester '{$semesterLevels['semester_name']}' not found, skipping.");
                continue;
            }

            $this->command->info("Creating levels for semester: {$semester->semester_name}");

            foreach ($semesterLevels['levels'] as $levelInfo) {
                // Check if level already exists for this semester
                $existingLevel = Level::where('semester_id', $semester->id)
                    ->where('name', $levelInfo['name'])
                    ->first();

                if (!$existingLevel) {
                    Level::create([
                        'semester_id' => $semester->id,
                        'name' => $levelInfo['name'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $levelsCreated++;
                    $this->command->info("Created level: {$levelInfo['name']} for {$semester->semester_name}");
                } else {
                    $this->command->info("Level {$levelInfo['name']} already exists for {$semester->semester_name}");
                }
            }
        }

       
        $specializedLevels = [
            ['name' => 'Pre-Degree', 'description' => 'Preparatory program for university admission'],
            ['name' => 'Remedial', 'description' => 'Remedial program for academic improvement'],
            ['name' => 'Postgraduate', 'description' => 'Masters and PhD level students'],
        ];

        $firstSemester = $semesters->first();
        $this->command->info("Creating specialized levels for: {$firstSemester->semester_name}");
        
        foreach ($specializedLevels as $specialLevel) {
            $existingLevel = Level::where('semester_id', $firstSemester->id)
                ->where('name', $specialLevel['name'])
                ->first();

            if (!$existingLevel) {
                Level::create([
                    'semester_id' => $firstSemester->id,
                    'name' => $specialLevel['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $levelsCreated++;
                $this->command->info("Created specialized level: {$specialLevel['name']}");
            }
        }

        $this->command->info("Level seeder completed successfully! Created {$levelsCreated} new level records.");
        $this->command->info('Total levels in database: ' . Level::count());
        
        // Show what levels were created
        $allLevels = Level::with('semester')->get();
        $this->command->info('Levels by semester:');
        foreach ($allLevels->groupBy('semester.semester_name') as $semesterName => $levels) {
            $this->command->line("  {$semesterName}: " . $levels->pluck('name')->implode(', '));
        }
    }
}