<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // Dashboard
            'dashboard.view',
            // 'dashboard.stats',
            /**
             * manage permissions
             */
            // Users/Roles/Permissions
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',


            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',
            'settings.system',
            'settings.smtp',
            'settings.admin',
            'settings.mail',


            // Orders
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',
            'orders.complete',
            'orders.cancel',
            'orders.approve',
            'tables.view',
            'tables.create',
            'tables.edit',
            'tables.delete',
            'tables.manage',

            // Payments
            'payments.view',
            'payments.create',
            'payments.refund',
            'payments.void',
            'payments.apply_discount',
            'payments.open_cash_drawer',
            'payments.end_shift',
            'payments.split_bill',
            'payments.print_receipt',



        ];

        foreach (array_unique($permissions) as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
        }
    }
}
