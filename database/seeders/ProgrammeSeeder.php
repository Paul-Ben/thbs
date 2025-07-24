<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Programme;
class ProgrammeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Programme::insert([
            [
                'name'       => 'Nursing',
                'level'      => '100',
                'college_id' => 1,  
            ],
            [
                'name'       => 'Medical Laboratory Science',
                'level'      => '200',
                'college_id' => 2,  
            ],
            [
                'name'       => 'Anatomy',
                'level'      => '100',
                'college_id' => 3,  
            ],
        ]);
    }
}
