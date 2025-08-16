<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $roles = [
            'Superadmin',
            'College Admin',
            'Admissions Officer',
            'Bursar',
            'IT Admin',
            'Student',
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Create permissions
        $permissions = [
            'view dashboard',
            'edit user',
            'view user',
            'delete user',
            'view college',
            'edit college',
            'view admissions',
            'edit admissions',
            'view finance',
            'edit finance',
            'view payments',
            'view it',
            'edit it',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $superAdminRole = Role::findByName('Superadmin');
        $superAdminRole->givePermissionTo(Permission::all());

        $collegeAdminRole = Role::findByName('College Admin');
        $collegeAdminRole->givePermissionTo([
            'view dashboard',
            'view college',
            'edit college',
        ]);

        $admissionsOfficerRole = Role::findByName('Admissions Officer');
        $admissionsOfficerRole->givePermissionTo([
            'view dashboard',
            'view admissions',
            'edit admissions',
        ]);

        $bursarRole = Role::findByName('Bursar');
        $bursarRole->givePermissionTo([
            'view dashboard',
            'view finance',
            'edit finance',
        ]);

        $itAdminRole = Role::findByName('IT Admin');
        $itAdminRole->givePermissionTo([
            'view dashboard',
            'view it',
            'edit it',
        ]);

        $studentRole = Role::findByName('Student');
        $studentRole->givePermissionTo([
            'view dashboard',
        ]);
    }
}
