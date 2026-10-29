<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityCategory; // استفاده از مدل

class ActivityCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'فناوری اطلاعات', 'slug' => 'it', 'is_active' => true, 'order' => 1],
            ['name' => 'هوش مصنوعی', 'slug' => 'ai', 'is_active' => true, 'order' => 2],
            ['name' => 'زیست فناوری', 'slug' => 'biotech', 'is_active' => true, 'order' => 3],
            ['name' => 'انرژی', 'slug' => 'energy', 'is_active' => true, 'order' => 4],
            ['name' => 'مواد پیشرفته', 'slug' => 'materials', 'is_active' => true, 'order' => 5],
        ];

        foreach ($categories as $category) {
            ActivityCategory::updateOrCreate(
                ['slug' => $category['slug']], // شرط جستجو
                $category                      // مقادیر برای آپدیت یا ایجاد
            );
        }
    }
}