<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ApplicationFee;

class ApplicationFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ApplicationFee::insert([
            [
                'programme_id' => 1, // Nursing
                'amount' => 5000.00,
            ],
            [
                'programme_id' => 2, // Medical Laboratory Science
                'amount' => 8000.00,
            ],
            [
                'programme_id' => 3, // Anatomy
                'amount' => 6500.00,
            ],
        ]);
    }
}
