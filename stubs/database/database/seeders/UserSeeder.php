<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
            ]
        );

        // Create or get Admin role
        $role = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => 'web']
        );

        // Get all permissions
        $permissions = Permission::all();

        // Assign all permissions to Admin role
        $role->syncPermissions($permissions);

        // Assign role to user (IMPORTANT: use name, not id)
        if (!$user->hasRole('Admin')) {
            $user->assignRole('Admin');
        }

        $permissions = [
            'dashboard-view',
            'role-view',
            'role-create',
            'role-edit',
            'role-delete',
            'report-view',
            'report-create',
            'report-edit',
            'report-delete',
            'permission-view',
            'permission-create',
            'permission-edit',
            'permission-delete'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Roles
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $user  = Role::firstOrCreate(['name' => 'User']);

        // Assign permissions
        $admin->givePermissionTo(Permission::all());
        $user->givePermissionTo(['dashboard-view']);
    }
}
