<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\College;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        College::insert([
            ['name' => 'College of Health Sciences'],
            ['name' => 'College of Applied Medical Sciences'],
            ['name' => 'College of Basic Medical Sciences'],
        ]);
    }
}
