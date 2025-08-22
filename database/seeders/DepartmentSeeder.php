<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::insert([
            ['code' => 'DOHS', 'name' => 'Department of Health Sciences', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'DAMS', 'name' => 'Department of Applied Medical Sciences', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'DBMS', 'name' => 'Department of Basic Medical Sciences', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
