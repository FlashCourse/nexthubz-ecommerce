<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;

class SettingsService
{
    protected $cacheDuration = 60 * 60 * 24; // Cache for 24 hours

    /**
     * Get a specific setting value with automated key existence check.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        // Check if the setting is cached, if not, fetch from the database and cache it
        return Cache::remember("setting_{$key}", $this->cacheDuration, function () use ($key, $default) {
            return Setting::where('key', $key)->value('value') ?? $default;
        });
    }

    /**
     * Get all settings.
     *
     * @return array
     */
    public function all()
    {
        // Cache all settings to avoid multiple database hits
        return Cache::remember("all_settings", $this->cacheDuration, function () {
            return Setting::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Check if a specific setting key exists.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        $settings = $this->all(); // Get all settings to reduce database queries
        return array_key_exists($key, $settings);
    }

    /**
     * Update or create a setting and refresh the cache.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function update($key, $value)
    {
        // Update or create the setting in the database
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);

        // Clear the specific setting cache and the cache of all settings
        Cache::forget("setting_{$key}");
        Cache::forget("all_settings");

        // Refresh settings in the cache
        $this->cacheSettings();

        // Update the Laravel configuration
        $this->updateConfig();
    }

    /**
     * Cache all settings for performance.
     *
     * @return void
     */
    public function cacheSettings()
    {
        Cache::forget('all_settings'); // Clear the all_settings cache
        $this->all(); // Re-cache all settings
    }

    /**
     * Update configuration values dynamically based on settings.
     *
     * @return void
     */
    public function updateConfig()
    {
        $settings = $this->all(); // Get all settings

        // Ensure all keys are checked for existence before accessing their values
        config([
            'sslcommerz.apiCredentials.store_id' => $this->has('sslcommerz_store_id') ? $settings['sslcommerz_store_id'] : env('SSLCZ_STORE_ID'),
            'sslcommerz.apiCredentials.store_password' => $this->has('sslcommerz_store_password') ? $settings['sslcommerz_store_password'] : env('SSLCZ_STORE_PASSWORD'),
            'sslcommerz.apiDomain' => $this->has('sslcommerz_testmode') && $settings['sslcommerz_testmode'] ? "https://sandbox.sslcommerz.com" : "https://securepay.sslcommerz.com",
            'sslcommerz.connect_from_localhost' => $this->has('sslcommerz_is_localhost') ? $settings['sslcommerz_is_localhost'] : env('IS_LOCALHOST'),
        ]);
    }
}
