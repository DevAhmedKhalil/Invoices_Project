<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'عرض صلاحية',
            'اضافة صلاحية',
            'تعديل صلاحية',
            'حذف صلاحية',
            'عرض مستخدم',
            'اضافة مستخدم',
            'تعديل مستخدم',
            'حذف مستخدم',
            'عرض فاتورة',
            'اضافة فاتورة',
            'تعديل فاتورة',
            'حذف فاتورة',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
