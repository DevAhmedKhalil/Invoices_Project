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
        $user = User::create([
            'name' => 'Admin AK',
            'email' => 'admin@domain.com',
            'password' => bcrypt('123456789'),
//            'roles_name' => "admin",
            'status' => 'مفعل',
        ]);

        $role = Role::create(['name' => 'admin']);

        $permissions = Permission::pluck('id','id')->all();

        $role->syncPermissions($permissions);

        $user->assignRole([$role->id]);
    }
}
