<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call(RoleAndPermissionSeeder::class);
       $this->call(AdminUserSeeder::class);
       $this->call(CollegeSeeder::class);
       $this->call(ProgrammeSeeder::class);
       $this->call(SchoolSession::class);
       $this->call(ApplicationSessionSeeder::class);
    }
}
