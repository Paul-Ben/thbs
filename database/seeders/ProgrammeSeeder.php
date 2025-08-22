<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Programme;
use App\Models\Level;
class ProgrammeSeeder extends Seeder
{ 
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resolve Level IDs by name. Fallback to the first available level if not found.
        $level100 = Level::where('name', 'like', '100%')->first();
        $level200 = Level::where('name', 'like', '200%')->first();
        $fallback = Level::first();

        Programme::insert([
            [
                'code'       => 'NRS',
                'name'       => 'Nursing',
                'level_id'   => optional($level100 ?: $fallback)->id,
                'department_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code'       => 'MLS',
                'name'       => 'Medical Laboratory Science',
                'level_id'   => optional($level200 ?: $fallback)->id,
                'department_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code'       => 'ANAT',
                'name'       => 'Anatomy',
                'level_id'   => optional($level100 ?: $fallback)->id,
                'department_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
