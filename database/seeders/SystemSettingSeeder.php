<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        SystemSetting::truncate();

        SystemSetting::create([
            'system_title'         => 'My Application',
            'system_short_title'   => 'App',
            'tag_line'             => 'Best Ecommerce Platform',
            'company_name'         => 'My Company Ltd.',
            'phone_code'           => '+880',
            'phone_number'         => '1234567890',
            'email'                => 'admin@admin.com',
            'copyright_text'       => '© 2026 My Company',
            'admin_title'          => 'Admin Panel',
            'admin_short_title'    => 'Admin',
            'admin_copyright_text' => '© 2026 Admin Panel',

        ]);
    }
}
