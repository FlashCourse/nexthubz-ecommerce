<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class SettingsService
{
    protected $cacheDuration = 60; // Cache for 1 minutes

    /**
     * Get a specific setting value with automated key existence check.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        // First, check if the setting exists in the cache
        $cachedValue = Cache::get("setting_{$key}");

        // If the cached value is not set or is empty, fetch from the database
        if ($cachedValue === null || $cachedValue === '') {
            // Fetch the value from the database
            $value = Setting::where('key', $key)->value('value');

            // Check if the value exists and is not empty
            if ($value !== null && $value !== '') {
                // Cache the value if it exists and is not empty
                Cache::put("setting_{$key}", $value, $this->cacheDuration);
                return $value;
            }

            // If the value does not exist or is empty, return the default value
            return $default;
        }

        // If the cached value is found and not empty, return it
        return $cachedValue;
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
        // Ensure all keys are checked for existence before accessing their values
        config([
            // SSLCommerz configuration
            'sslcommerz.apiCredentials.store_id' => $this->get('sslcommerz_store_id', env('SSLCZ_STORE_ID')),
            'sslcommerz.apiCredentials.store_password' => $this->get('sslcommerz_store_password', env('SSLCZ_STORE_PASSWORD')),
            'sslcommerz.apiDomain' => $this->get('sslcommerz_testmode', false) ? "https://securepay.sslcommerz.com" : "https://sandbox.sslcommerz.com",
            'sslcommerz.connect_from_localhost' => $this->get('sslcommerz_testmode', env('IS_LOCALHOST')),

            // bKash configuration
            'bkash.base_url' => $this->get('bkash_base_url', env('BKASH_BASE_URL')),
            'bkash.app_key' => $this->get('bkash_app_key', env('BKASH_APP_KEY')),
            'bkash.app_secret' => $this->get('bkash_app_secret', env('BKASH_APP_SECRET')),
            'bkash.username' => $this->get('bkash_username', env('BKASH_USERNAME')),
            'bkash.password' => $this->get('bkash_password', env('BKASH_PASSWORD')),

            // Mailer configuration
            'mail.from.address' => $this->get('email_from_address', env('MAIL_FROM_ADDRESS')),
            'mail.from.name' => $this->get('email_from_name', env('MAIL_FROM_NAME')),
            'mail.mailers.smtp.host' => $this->get('email_smtp_host', env('MAIL_HOST')),
            'mail.mailers.smtp.port' => $this->get('email_smtp_port', env('MAIL_PORT', 587)),
            'mail.mailers.smtp.username' => $this->get('email_smtp_username', env('MAIL_USERNAME')),
            'mail.mailers.smtp.password' => $this->get('email_smtp_password', env('MAIL_PASSWORD')),
        ]);
    }
}
