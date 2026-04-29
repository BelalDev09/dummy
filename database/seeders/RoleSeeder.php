<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch all permissions
        $allPermissions = Permission::all();

        // Roles and their permissions
        $rolesPermissions = [
            'superadmin' => $allPermissions,
            'admin'      => $allPermissions,

        ];

        // Create roles and assign permissions
        foreach ($rolesPermissions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }
    }
}
