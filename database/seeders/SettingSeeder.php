<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'مرکز رشد مشگین شهر', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_version', 'value' => '1.0', 'group' => 'general', 'type' => 'string'],
            ['key' => 'site_logo', 'value' => '', 'group' => 'general', 'type' => 'image'],
            ['key' => 'contact_email', 'value' => 'info@example.com', 'group' => 'contact', 'type' => 'string'],
            ['key' => 'contact_phone', 'value' => '02100000000', 'group' => 'contact', 'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}