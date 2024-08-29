<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Services\SettingsService;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register the SettingsService as a singleton
        $this->app->singleton(SettingsService::class, function ($app) {
            return new SettingsService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @param SettingsService $settings
     * @return void
     */
    public function boot(SettingsService $settings)
    {
        // Cache settings for performance
        $settings->cacheSettings();

        // Update configuration values dynamically
        $settings->updateConfig();

        // Share the SettingsService instance with all views
        View::composer('*', function ($view) use ($settings) {
            // Share the SettingsService instance instead of raw settings array
            $view->with('settings', $settings);
        });
    }
}
