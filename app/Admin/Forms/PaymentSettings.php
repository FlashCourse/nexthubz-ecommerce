<?php

namespace App\Admin\Forms;

use OpenAdmin\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Models\Setting;

class PaymentSettings extends Form
{
    public $title = 'Payment Settings';

    public function handle(Request $request)
    {
        $data = $request->all();

        // Define a list of all switch keys
        $switchKeys = [
            'paypal_active',
            'stripe_active',
            'offline_active',
            'sslcommerz_active',
            'bkash_active' // Added bKash
        ];

        // Set all switch fields to '0' (inactive) initially
        foreach ($switchKeys as $key) {
            $data[$key] = 0;
        }

        // Check if switch was toggled on and update accordingly
        foreach ($switchKeys as $key) {
            if ($request->has("{$key}_cb")) {
                $data[$key] = 1;
            }
        }

        // Convert JSON fields to arrays
        $data['paypal'] = json_encode($request->input('paypal', ''));
        $data['stripe'] = json_encode($request->input('stripe', ''));
        $data['offline'] = json_encode($request->input('offline', ''));
        $data['sslcommerz'] = json_encode($request->input('sslcommerz', ''));
        $data['bkash'] = json_encode($request->input('bkash', '')); // Added bKash

        // Update or create each setting based on processed data
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        admin_toastr('Saved Successfully', 'success');

        return back();
    }

    public function form()
    {
        // Offline Payment Settings (Top)
        $this->divider('Offline Payment Settings');
        $this->switch('offline_active', 'Activate Offline Payment')
            ->help('Enable or disable offline payment method.')
            ->default((int) $this->getSettingValue('offline_active')) // Cast to int for boolean display
            ->disable(); // Disable input

        $this->textarea('offline_instructions', 'Offline Payment Instructions')
            ->rules('required_if:offline_active,true')
            ->help('Provide instructions for offline payment.')
            ->default($this->getSettingValue('offline_instructions'))
            ->disable(); // Disable input

        // PayPal Settings (Disabled by Default)
        $this->divider('PayPal Settings');
        $this->switch('paypal_active', 'Activate PayPal')
            ->help('Enable or disable PayPal as a payment method.')
            ->default((int) $this->getSettingValue('paypal_active')) // Cast to int for boolean display
            ->disable(); // Disable input

        $this->text('paypal_client_id', 'PayPal Client ID')
            ->rules('required_if:paypal_active,true')
            ->help('Enter your PayPal Client ID.')
            ->default($this->getSettingValue('paypal_client_id'))
            ->disable(); // Disable input

        $this->text('paypal_client_secret', 'PayPal Client Secret')
            ->rules('required_if:paypal_active,true')
            ->help('Enter your PayPal Client Secret.')
            ->default($this->getSettingValue('paypal_client_secret'))
            ->disable(); // Disable input

        // Stripe Settings (Disabled by Default)
        $this->divider('Stripe Settings');
        $this->switch('stripe_active', 'Activate Stripe')
            ->help('Enable or disable Stripe as a payment method.')
            ->default((int) $this->getSettingValue('stripe_active')) // Cast to int for boolean display
            ->disable(); // Disable input

        $this->text('stripe_key', 'Stripe Key')
            ->rules('required_if:stripe_active,true')
            ->help('Enter your Stripe Key.')
            ->default($this->getSettingValue('stripe_key'))
            ->disable(); // Disable input

        $this->text('stripe_secret', 'Stripe Secret')
            ->rules('required_if:stripe_active,true')
            ->help('Enter your Stripe Secret.')
            ->default($this->getSettingValue('stripe_secret'))
            ->disable(); // Disable input

        // SSLCommerz Settings (Disabled by Default)
        $this->divider('SSLCommerz Settings');
        $this->switch('sslcommerz_active', 'Activate SSLCommerz')
            ->help('Enable or disable SSLCommerz as a payment method.')
            ->default((int) $this->getSettingValue('sslcommerz_active')) // Cast to int for boolean display
            ->disable(); // Disable input

        $this->text('sslcommerz_store_id', 'SSLCommerz Store ID')
            ->rules('required_if:sslcommerz_active,true')
            ->help('Enter your SSLCommerz Store ID.')
            ->default($this->getSettingValue('sslcommerz_store_id'))
            ->disable(); // Disable input

        $this->text('sslcommerz_store_password', 'SSLCommerz Store Password')
            ->rules('required_if:sslcommerz_active,true')
            ->help('Enter your SSLCommerz Store Password.')
            ->default($this->getSettingValue('sslcommerz_store_password'))
            ->disable(); // Disable input

        $this->switch('sslcommerz_testmode', 'Test Mode')
            ->help('Enable or disable test mode for SSLCommerz.')
            ->default((int) $this->getSettingValue('sslcommerz_testmode')) // Cast to int for boolean display
            ->disable(); // Disable input

        // bKash Settings (Disabled by Default)
        $this->divider('bKash Settings');
        $this->switch('bkash_active', 'Activate bKash')
            ->help('Enable or disable bKash as a payment method.')
            ->default((int) $this->getSettingValue('bkash_active')) // Cast to int for boolean display
            ->disable(); // Disable input

        $this->text('bkash_base_url', 'bKash Base URL')
            ->rules('required_if:bkash_active,true')
            ->help('Enter your bKash Base URL.')
            ->default($this->getSettingValue('bkash_base_url'))
            ->disable(); // Disable input

        $this->text('bkash_app_key', 'bKash App Key')
            ->rules('required_if:bkash_active,true')
            ->help('Enter your bKash App Key.')
            ->default($this->getSettingValue('bkash_app_key'))
            ->disable(); // Disable input

        $this->text('bkash_app_secret', 'bKash App Secret')
            ->rules('required_if:bkash_active,true')
            ->help('Enter your bKash App Secret.')
            ->default($this->getSettingValue('bkash_app_secret'))
            ->disable(); // Disable input

        $this->text('bkash_username', 'bKash Username')
            ->rules('required_if:bkash_active,true')
            ->help('Enter your bKash Username.')
            ->default($this->getSettingValue('bkash_username'))
            ->disable(); // Disable input

        $this->text('bkash_password', 'bKash Password')
            ->rules('required_if:bkash_active,true')
            ->help('Enter your bKash Password.')
            ->default($this->getSettingValue('bkash_password'))
            ->disable(); // Disable input

        // Disable reset button and change submit button text
        $this->disableReset();
    }

    public function data()
    {
        return [
            'paypal_active' => (int) $this->getSettingValue('paypal_active'), // Cast to int for boolean display
            'paypal_client_id' => $this->getSettingValue('paypal_client_id'),
            'paypal_client_secret' => $this->getSettingValue('paypal_client_secret'),
            'stripe_active' => (int) $this->getSettingValue('stripe_active'), // Cast to int for boolean display
            'stripe_key' => $this->getSettingValue('stripe_key'),
            'stripe_secret' => $this->getSettingValue('stripe_secret'),
            'offline_active' => (int) $this->getSettingValue('offline_active'), // Cast to int for boolean display
            'offline_instructions' => $this->getSettingValue('offline_instructions'),
            'sslcommerz_active' => (int) $this->getSettingValue('sslcommerz_active'), // Cast to int for boolean display
            'sslcommerz_store_id' => $this->getSettingValue('sslcommerz_store_id'),
            'sslcommerz_store_password' => $this->getSettingValue('sslcommerz_store_password'),
            'sslcommerz_testmode' => (int) $this->getSettingValue('sslcommerz_testmode'), // Cast to int for boolean display
            'bkash_active' => (int) $this->getSettingValue('bkash_active'), // Cast to int for boolean display
            'bkash_base_url' => $this->getSettingValue('bkash_base_url'),
            'bkash_app_key' => $this->getSettingValue('bkash_app_key'),
            'bkash_app_secret' => $this->getSettingValue('bkash_app_secret'),
            'bkash_username' => $this->getSettingValue('bkash_username'),
            'bkash_password' => $this->getSettingValue('bkash_password'),
        ];
    }

    protected function getSettingValue($key)
    {
        return Setting::where('key', $key)->first()?->value ?? '';
    }
}
