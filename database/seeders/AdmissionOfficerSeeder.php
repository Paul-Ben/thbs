<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class AdmissionOfficerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
            ['email' => 'admission@bsuth.edu.ng'],
            [
                'name' => 'Admission Officer',
                'password' => bcrypt('password'),
                'userRole' => 'Admission Officer',
            ]
        );

        // Assign role to user
        if (!$admissionOfficer->hasRole('Admission Officer')) {
            $admissionOfficer->assignRole('Admission Officer');
        }

    }
}
