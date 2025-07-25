<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (file_exists(app_path('Helpers/CustomHelper.php'))) {
            require_once app_path('Helpers/CustomHelper.php');
        }

        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ✅ Force HTTPS in production
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // ✅ Fix for older MySQL versions (max key length issue)
        Schema::defaultStringLength(191);
    }
}
