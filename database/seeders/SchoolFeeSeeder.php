<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolFee;
use App\Models\Programme;
use App\Models\SchoolSession;
use App\Models\Semester;
use App\Models\Level;
use Carbon\Carbon;

class SchoolFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing records to create realistic relationships
        $programmes = Programme::all();
        $sessions = SchoolSession::all();
        $semesters = Semester::all();
        $levels = Level::all();

        // If no data exists, create some basic records first
        if ($programmes->isEmpty() || $sessions->isEmpty() || $semesters->isEmpty() || $levels->isEmpty()) {
            $this->command->warn('Please ensure programmes, school_sessions, semesters, and levels tables have data before running this seeder.');
            return;
        }

        // Sample fee types and their typical amounts
        $feeTypes = [
            'tuition' => [
                'name' => 'Tuition Fee',
                'amounts' => [150000, 180000, 200000, 220000] // Different amounts for different levels
            ],
            'accommodation' => [
                'name' => 'Accommodation Fee',
                'amounts' => [80000, 85000, 90000, 95000]
            ],
            'library' => [
                'name' => 'Library Fee',
                'amounts' => [15000, 15000, 20000, 20000]
            ],
            'laboratory' => [
                'name' => 'Laboratory Fee',
                'amounts' => [25000, 30000, 35000, 40000]
            ],
            'sports' => [
                'name' => 'Sports Fee',
                'amounts' => [10000, 10000, 12000, 12000]
            ],
            'medical' => [
                'name' => 'Medical Fee',
                'amounts' => [20000, 20000, 25000, 25000]
            ],
            'development' => [
                'name' => 'Development Levy',
                'amounts' => [50000, 50000, 60000, 60000]
            ],
            'examination' => [
                'name' => 'Examination Fee',
                'amounts' => [15000, 18000, 20000, 22000]
            ]
        ];

        $schoolFees = [];
        $currentSession = $sessions->first(); // Use first session for sample data

        foreach ($programmes as $programme) {
            foreach ($semesters as $semester) {
                foreach ($levels as $levelIndex => $level) {
                    foreach ($feeTypes as $feeTypeKey => $feeTypeData) {
                        // Use level index to vary amounts (0-3 for different levels)
                        $amountIndex = min($levelIndex, 3);
                        $amount = $feeTypeData['amounts'][$amountIndex];

                        $schoolFees[] = [
                            'programme_id' => $programme->id,
                            'school_session_id' => $currentSession->id,
                            'semester_id' => $semester->id,
                            'level_id' => $level->id,
                            'name' => $feeTypeData['name'] . ' - ' . $programme->name . ' (' . $level->name . ')',
                            'amount' => $amount,
                            'currency' => 'NGN',
                            'description' => $feeTypeData['name'] . ' for ' . $programme->name . ' students at ' . $level->name . ' level in ' . $semester->name . ' semester',
                            'is_active' => true,
                            'fee_type' => $feeTypeKey,
                            'due_date' => Carbon::now()->addMonths(2)->format('Y-m-d'), // Due in 2 months
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }
            }
        }

        // Insert in chunks to avoid memory issues
        $chunks = array_chunk($schoolFees, 100);
        foreach ($chunks as $chunk) {
            SchoolFee::insert($chunk);
        }

        $this->command->info('School fees seeded successfully! Created ' . count($schoolFees) . ' fee records.');
    }
}
