<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $superadminRole = Role::firstOrCreate(['name' => 'Superadmin']);
        
        // Create permissions if needed
        $superpermissions = [
            'view dashboard',
            'manage users',
            // Add other permissions as needed
        ];
        
        foreach ($superpermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Assign all permissions to superadmin role
        $superadminRole->givePermissionTo(Permission::all());

        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'userRole' => 'Superadmin',
            ]
        );


        $admissionOfficerRole = Role::firstOrCreate(['name' => 'Admission Officer']);
        
        // Create permissions if needed
        $permissions = [
            'view dashboard',
            'view admissions',
            'edit admissions',
        ];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Assign all permissions to admission officer role
        $admissionOfficerRole->givePermissionTo(Permission::all());

        // create admissions officer if not exists
        $admissionOfficer = User::firstOrCreate(
            ['email' => 'admission@example.com'],
            [
                'name' => 'Admission Officer',
                'password' => bcrypt('password'),
                'userRole' => 'Admission Officer',
            ]
        );

        // Assign role to user
        if (!$admin->hasRole('Superadmin')) {
            $admin->assignRole('Superadmin');
        }

        if (!$admissionOfficer->hasRole('Admission Officer')) {
            $admissionOfficer->assignRole('Admission Officer');
        }
    }
}
