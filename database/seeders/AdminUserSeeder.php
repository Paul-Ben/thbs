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
        $permissions = [
            'view dashboard',
            'manage users',
            // Add other permissions as needed
        ];
        
        foreach ($permissions as $permission) {
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

        // Assign role to user
        if (!$admin->hasRole('Superadmin')) {
            $admin->assignRole('Superadmin');
        }
    }
}
