<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['national_code' => '1672453356'], // شرط پیدا کردن کاربر
            [
                'password' => Hash::make('password'),
                'name' => 'Admin',
                'family' => 'System',
                'mobile' => '09120000000',
                'status' => 'active',
                'is_innovator' => false,
            ]
        );

        // متد صحیح در پکیج Spatie
        $user->assignRole('super_admin'); 
    }
}