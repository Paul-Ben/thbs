<?php

namespace Database\Seeders;

use App\Models\SchoolSession as SchoolSessionModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSession extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sessions = [
            [
                'session_name' => '2020/2021',
                'year' => 2020,
                'is_current' => false,
            ],
            [
                'session_name' => '2021/2022',
                'year' => 2021,
                'is_current' => false,
            ],
            [
                'session_name' => '2022/2023',
                'year' => 2022,
                'is_current' => false,
            ],
            [
                'session_name' => '2023/2024',
                'year' => 2023,
                'is_current' => false,
            ],
            [
                'session_name' => '2024/2025',
                'year' => 2024,
                'is_current' => false,
            ],
            [
                'session_name' => '2025/2026',
                'year' => 2025,
                'is_current' => true,
            ],
        ];

        foreach ($sessions as $session) {
            SchoolSessionModel::create($session);
        }
    }
}
