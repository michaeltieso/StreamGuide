<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // Only load app name if the table exists
        if (Schema::hasTable('site_settings')) {
            $appName = SiteSetting::get('app_name');
            if ($appName) {
                config(['app.name' => $appName]);
            }
        }
    }
}
