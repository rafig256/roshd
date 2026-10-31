<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\View;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    
    public function boot(): void
    {
        // پاس دادن تنظیمات به تمام ویوها
        View::composer('*', function ($view) {
            $settings = Setting::pluck('value', 'key')->toArray();
            $view->with('site_settings', $settings);
        });
    }
}
