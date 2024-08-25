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

        // Convert JSON fields to arrays
        $data['paypal'] = json_encode($data['paypal']);
        $data['stripe'] = json_encode($data['stripe']);
        $data['offline'] = json_encode($data['offline']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        admin_toastr('Saved Successfully', 'success');

        return back();
    }

    public function form()
    {
        // PayPal Settings
        $this->divider('PayPal Settings');
        $this->switch('paypal_active', 'Activate PayPal')
            ->help('Enable or disable PayPal as a payment method.')
            ->default($this->getSettingValue('paypal_active') === 'true');

        $this->text('paypal_client_id', 'PayPal Client ID')
            ->rules('required_if:paypal_active,true')
            ->help('Enter your PayPal Client ID.')
            ->default($this->getSettingValue('paypal_client_id'));

        $this->text('paypal_client_secret', 'PayPal Client Secret')
            ->rules('required_if:paypal_active,true')
            ->help('Enter your PayPal Client Secret.')
            ->default($this->getSettingValue('paypal_client_secret'));

        // Stripe Settings
        $this->divider('Stripe Settings');
        $this->switch('stripe_active', 'Activate Stripe')
            ->help('Enable or disable Stripe as a payment method.')
            ->default($this->getSettingValue('stripe_active') === 'true');

        $this->text('stripe_key', 'Stripe Key')
            ->rules('required_if:stripe_active,true')
            ->help('Enter your Stripe Key.')
            ->default($this->getSettingValue('stripe_key'));

        $this->text('stripe_secret', 'Stripe Secret')
            ->rules('required_if:stripe_active,true')
            ->help('Enter your Stripe Secret.')
            ->default($this->getSettingValue('stripe_secret'));

        // Offline Payment Settings
        $this->divider('Offline Payment Settings');
        $this->switch('offline_active', 'Activate Offline Payment')
            ->help('Enable or disable offline payment method.')
            ->default($this->getSettingValue('offline_active') === 'true');

        $this->textarea('offline_instructions', 'Offline Payment Instructions')
            ->rules('required_if:offline_active,true')
            ->help('Provide instructions for offline payment.')
            ->default($this->getSettingValue('offline_instructions'));

        // SSLCommerz Settings
        $this->divider('SSLCommerz Settings');
        $this->switch('sslcommerz_active', 'Activate SSLCommerz')
            ->help('Enable or disable SSLCommerz as a payment method.')
            ->default($this->getSettingValue('sslcommerz_active') === 'true');

        $this->text('sslcommerz_store_id', 'SSLCommerz Store ID')
            ->rules('required_if:sslcommerz_active,true')
            ->help('Enter your SSLCommerz Store ID.')
            ->default($this->getSettingValue('sslcommerz_store_id'));

        $this->text('sslcommerz_store_password', 'SSLCommerz Store Password')
            ->rules('required_if:sslcommerz_active,true')
            ->help('Enter your SSLCommerz Store Password.')
            ->default($this->getSettingValue('sslcommerz_store_password'));

        $this->switch('sslcommerz_testmode', 'Test Mode')
            ->help('Enable or disable test mode for SSLCommerz.')
            ->default($this->getSettingValue('sslcommerz_testmode') === 'true');

        // Disable reset button and change submit button text
        $this->disableReset();
    }

    public function data()
    {
        return [
            'payment_settings_active' => $this->getSettingValue('payment_settings_active') === 'true',
            'paypal_active' => $this->getSettingValue('paypal_active') === 'true',
            'paypal_client_id' => $this->getSettingValue('paypal_client_id'),
            'paypal_client_secret' => $this->getSettingValue('paypal_client_secret'),
            'stripe_active' => $this->getSettingValue('stripe_active') === 'true',
            'stripe_key' => $this->getSettingValue('stripe_key'),
            'stripe_secret' => $this->getSettingValue('stripe_secret'),
            'offline_active' => $this->getSettingValue('offline_active') === 'true',
            'offline_instructions' => $this->getSettingValue('offline_instructions'),
            'sslcommerz_active' => $this->getSettingValue('sslcommerz_active') === 'true',
            'sslcommerz_store_id' => $this->getSettingValue('sslcommerz_store_id'),
            'sslcommerz_store_password' => $this->getSettingValue('sslcommerz_store_password'),
            'sslcommerz_testmode' => $this->getSettingValue('sslcommerz_testmode') === 'true',
        ];
    }

    protected function getSettingValue($key)
    {
        return Setting::where('key', $key)->first()?->value ?? '';
    }
}
