<?php

namespace App\Admin\Forms;

use OpenAdmin\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Models\Setting;

class GeneralSettings extends Form
{
    public $title = 'General Settings';

    public function handle(Request $request)
    {
        $data = $request->all();

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        admin_toastr('Saved Successfully', 'success');

        return back();
    }

    public function form()
    {
        // General Settings
        $this->text('site_name', 'Site Name')
            ->rules('required')
            ->help('Enter the name of your site')
            ->default($this->getSettingValue('site_name'));

        $this->image('site_logo', 'Site Logo')
            ->help('Upload the logo of your site')
            ->default($this->getSettingValue('site_logo'));

        $this->text('tagline', 'Tagline')
            ->help('Enter the tagline of your site')
            ->default($this->getSettingValue('tagline'));

        $this->select('language', 'Language')
            ->options($this->getLanguages())
            ->rules('required')
            ->help('Select the default language for your site')
            ->default($this->getSettingValue('language'));

        $this->select('time_zone', 'Time Zone')
            ->options($this->getTimeZones())
            ->rules('required')
            ->help('Select the default time zone for your site')
            ->default($this->getSettingValue('time_zone'));

        $this->select('currency', 'Currency')
            ->options($this->getCurrencies())
            ->rules('required')
            ->help('Select the default currency for your site')
            ->default($this->getSettingValue('currency'));

        $this->text('date_format', 'Date Format')
            ->rules('required')
            ->help('Enter the date format for your site (e.g., Y-m-d)')
            ->default($this->getSettingValue('date_format'));

        $this->text('time_format', 'Time Format')
            ->rules('required')
            ->help('Enter the time format for your site (e.g., H:i)')
            ->default($this->getSettingValue('time_format'));

        // User Settings
        $this->divider('User Settings');
        $this->switch('user_registration', 'Enable User Registration')
            ->options($this->getBooleanStates())
            ->help('Enable or disable user registration')
            ->default($this->getSettingValue('user_registration'));

        $this->switch('account_approval', 'Require Account Approval')
            ->options($this->getBooleanStates())
            ->help('Require admin approval for new accounts')
            ->default($this->getSettingValue('account_approval'));

        $this->text('password_policy', 'Password Policy')
            ->help('Enter the password policy for users')
            ->default($this->getSettingValue('password_policy'));

        // Store Policies
        $this->divider('Store Policies');
        $this->textarea('privacy_policy', 'Privacy Policy')
            ->help('Enter the privacy policy for your site')
            ->default($this->getSettingValue('privacy_policy'));

        $this->textarea('terms_and_conditions', 'Terms and Conditions')
            ->help('Enter the terms and conditions for your site')
            ->default($this->getSettingValue('terms_and_conditions'));

        $this->textarea('return_policy', 'Return Policy')
            ->help('Enter the return policy for your site')
            ->default($this->getSettingValue('return_policy'));

        // Appearance
        $this->divider('Appearance');
        $this->select('homepage_layout', 'Homepage Layout')
            ->options($this->getHomepageLayouts())
            ->help('Select the layout for the homepage')
            ->default($this->getSettingValue('homepage_layout'));

        $this->select('theme_settings', 'Theme Settings')
            ->options($this->getThemes())
            ->help('Select and customize themes')
            ->default($this->getSettingValue('theme_settings'));

        $this->number('products_per_page', 'Number of Products per Page')
            ->help('Set how many products are displayed per page')
            ->default($this->getSettingValue('products_per_page'));

        $this->select('default_sorting_order', 'Default Sorting Order')
            ->options($this->getSortingOrders())
            ->help('Set the default sorting order for products')
            ->default($this->getSettingValue('default_sorting_order'));

        // SEO Settings
        $this->divider('SEO Settings');
        $this->text('meta_title', 'Meta Title')
            ->help('Set the meta title for your site')
            ->default($this->getSettingValue('meta_title'));

        $this->textarea('meta_description', 'Meta Description')
            ->help('Set the meta description for your site')
            ->default($this->getSettingValue('meta_description'));

        $this->textarea('meta_keywords', 'Meta Keywords')
            ->help('Set meta keywords for your site')
            ->default($this->getSettingValue('meta_keywords'));

        $this->text('url_structure', 'URL Structure')
            ->help('Customize the URL structure for your site')
            ->default($this->getSettingValue('url_structure'));

        // Social Media
        $this->divider('Social Media');
        $this->text('facebook_link', 'Facebook Link')
            ->help('Enter your Facebook profile link')
            ->default($this->getSettingValue('facebook_link'));

        $this->text('twitter_link', 'Twitter Link')
            ->help('Enter your Twitter profile link')
            ->default($this->getSettingValue('twitter_link'));

        $this->text('instagram_link', 'Instagram Link')
            ->help('Enter your Instagram profile link')
            ->default($this->getSettingValue('instagram_link'));

        $this->text('linkedin_link', 'LinkedIn Link')
            ->help('Enter your LinkedIn profile link')
            ->default($this->getSettingValue('linkedin_link'));

        $this->switch('social_sharing', 'Enable Social Sharing')
            ->options($this->getBooleanStates())
            ->help('Enable or disable social sharing buttons on product pages')
            ->default($this->getSettingValue('social_sharing'));

        // Analytics
        $this->divider('Analytics');
        $this->text('google_analytics_id', 'Google Analytics ID')
            ->help('Add your Google Analytics tracking ID')
            ->default($this->getSettingValue('google_analytics_id'));

        $this->text('facebook_pixel_id', 'Facebook Pixel ID')
            ->help('Add your Facebook Pixel ID')
            ->default($this->getSettingValue('facebook_pixel_id'));

        $this->textarea('custom_tracking_scripts', 'Custom Tracking Scripts')
            ->help('Add custom tracking scripts')
            ->default($this->getSettingValue('custom_tracking_scripts'));

        // Maintenance Mode
        $this->divider('Maintenance Mode');
        $this->switch('maintenance_mode', 'Enable Maintenance Mode')
            ->options($this->getBooleanStates())
            ->help('Toggle maintenance mode on or off')
            ->default($this->getSettingValue('maintenance_mode'));

        $this->textarea('maintenance_message', 'Maintenance Mode Message')
            ->help('Set a custom message to display when maintenance mode is enabled')
            ->default($this->getSettingValue('maintenance_message'));

        // Disable reset button and change submit button text
        $this->disableReset();
    }

    public function data()
    {
        return [
            'site_name' => $this->getSettingValue('site_name'),
            'site_logo' => $this->getSettingValue('site_logo'),
            'tagline' => $this->getSettingValue('tagline'),
            'language' => $this->getSettingValue('language'),
            'time_zone' => $this->getSettingValue('time_zone'),
            'currency' => $this->getSettingValue('currency'),
            'date_format' => $this->getSettingValue('date_format'),
            'time_format' => $this->getSettingValue('time_format'),
            'user_registration' => $this->getSettingValue('user_registration'),
            'account_approval' => $this->getSettingValue('account_approval'),
            'password_policy' => $this->getSettingValue('password_policy'),
            'privacy_policy' => $this->getSettingValue('privacy_policy'),
            'terms_and_conditions' => $this->getSettingValue('terms_and_conditions'),
            'return_policy' => $this->getSettingValue('return_policy'),
            'homepage_layout' => $this->getSettingValue('homepage_layout'),
            'theme_settings' => $this->getSettingValue('theme_settings'),
            'products_per_page' => $this->getSettingValue('products_per_page'),
            'default_sorting_order' => $this->getSettingValue('default_sorting_order'),
            'meta_title' => $this->getSettingValue('meta_title'),
            'meta_description' => $this->getSettingValue('meta_description'),
            'meta_keywords' => $this->getSettingValue('meta_keywords'),
            'url_structure' => $this->getSettingValue('url_structure'),
            'facebook_link' => $this->getSettingValue('facebook_link'),
            'twitter_link' => $this->getSettingValue('twitter_link'),
            'instagram_link' => $this->getSettingValue('instagram_link'),
            'linkedin_link' => $this->getSettingValue('linkedin_link'),
            'social_sharing' => $this->getSettingValue('social_sharing'),
            'google_analytics_id' => $this->getSettingValue('google_analytics_id'),
            'facebook_pixel_id' => $this->getSettingValue('facebook_pixel_id'),
            'custom_tracking_scripts' => $this->getSettingValue('custom_tracking_scripts'),
            'maintenance_mode' => $this->getSettingValue('maintenance_mode'),
            'maintenance_message' => $this->getSettingValue('maintenance_message'),
        ];
    }

    protected function getSettingValue($key)
    {
        return Setting::where('key', $key)->first()?->value;
    }

    protected function getLanguages()
    {
        // Add logic to retrieve available languages
        return [
            'en' => 'English',
            'es' => 'Spanish',
            'fr' => 'French',
        ];
    }

    protected function getTimeZones()
    {
        // Add logic to retrieve available time zones
        return [
            'UTC' => 'UTC',
            'PST' => 'Pacific Standard Time',
            'EST' => 'Eastern Standard Time',
        ];
    }

    protected function getCurrencies()
    {
        // Add logic to retrieve available currencies
        return [
            'USD' => 'USD',
            'EUR' => 'EUR',
            'GBP' => 'GBP',
        ];
    }

    protected function getHomepageLayouts()
    {
        // Add logic to retrieve available homepage layouts
        return [
            'grid' => 'Grid',
            'list' => 'List',
        ];
    }

    protected function getThemes()
    {
        // Add logic to retrieve available themes
        return [
            'default' => 'Default',
            'dark' => 'Dark',
            'light' => 'Light',
        ];
    }

    protected function getSortingOrders()
    {
        // Add logic to retrieve available sorting orders
        return [
            'newest' => 'Newest First',
            'price_low_high' => 'Price: Low to High',
            'price_high_low' => 'Price: High to Low',
        ];
    }

    protected function getBooleanStates()
    {
        return [
            1 => 'Enabled',
            0 => 'Disabled',
        ];
    }
}
