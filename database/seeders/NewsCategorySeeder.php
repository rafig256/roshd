<?php

namespace Database\Seeders;

use App\Models\NewsCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsCategorySeeder extends Seeder
{
    public function run(): void
    {
        NewsCategory::updateOrCreate(
            ['slug' => 'news'],
            [
                'name' => 'اخبار عمومی',
                'parent_id' => null,
                'description' => 'دسته‌بندی پیش‌فرض برای اخبار عمومی مرکز',
                'order' => 0,
                'is_active' => true,
            ]
        );
    }
}