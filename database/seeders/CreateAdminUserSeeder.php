<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create or retrieve the admin user by email
        $user = User::firstOrCreate(
            ['email' => 'admin@domain.com'], // Check if user already exists
            [
                'name' => 'Ahmed Khalil <3',
                'password' => bcrypt('123456789'),
                'roles_name' => ['owner'],
                'status' => 'مفعل',
            ]
        );

        // Create or retrieve the 'owner' role
        $role = Role::firstOrCreate(['name' => 'owner']);

        // Get all available permissions
        $permissions = Permission::pluck('id', 'id')->all();

        // Assign all permissions to the 'owner' role
        $role->syncPermissions($permissions);

        // Assign the role to the user by name
        $user->assignRole($role->name);
    }
}
