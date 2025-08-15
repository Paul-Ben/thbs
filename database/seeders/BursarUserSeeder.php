<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class BursarUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bursarRole = Role::firstOrCreate(['name' => 'Bursar']);
        
        // Create finance-related permissions if they don't exist
        $financePermissions = [
            'view dashboard',
            'view finance',
            'edit finance',
            'view payments'
        ];
        
        foreach ($financePermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Assign finance permissions to bursar role
        $bursarRole->givePermissionTo([
            'view dashboard',
            'view finance',
            'edit finance',
            'view payments',
        ]);

        // Create bursar user if not exists
        $bursar = User::firstOrCreate(
            ['email' => 'bursar@example.com'],
            [
                'name' => 'Finance Bursar',
                'password' => bcrypt('password'),
                'userRole' => 'Bursar',
            ]
        );

        // Assign role to user
        if (!$bursar->hasRole('Bursar')) {
            $bursar->assignRole('Bursar');
        }
    }
}
