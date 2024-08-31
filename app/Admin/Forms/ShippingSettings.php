<?php

namespace App\Admin\Forms;

use OpenAdmin\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Models\Setting;

class ShippingSettings extends Form
{
    public $title = 'Shipping Settings';

    public function handle(Request $request)
    {
        $data = $request->all();

        // Define a list of all shipping method keys
        $shippingMethods = [
            'free_shipping_active',
            'flat_rate_shipping_active',
            'table_rate_shipping_active',
            'carrier_shipping_active',
            'product_based_shipping_active'
        ];

        // Set all shipping methods to '0' (inactive) initially
        foreach ($shippingMethods as $method) {
            $data[$method] = 0;
        }

        // Activate the selected shipping method based on radio input
        if (!empty($request->input('active_shipping_method'))) {
            $activeMethod = $request->input('active_shipping_method');
            if (in_array($activeMethod, $shippingMethods)) {
                $data[$activeMethod] = 1;
            }
        }

        // Update or create each setting based on processed data
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        admin_toastr('Settings saved successfully!', 'success');

        return back();
    }

    public function form()
    {
        // General Shipping Settings with Radio Buttons
        $this->radio('active_shipping_method', 'Select Active Shipping Method')
            ->options([
                'free_shipping_active' => 'Free Shipping',
                'flat_rate_shipping_active' => 'Flat Rate Shipping',
                'table_rate_shipping_active' => 'Table Rate Shipping',
                'carrier_shipping_active' => 'Carrier Shipping',
                'product_based_shipping_active' => 'Product-Based Shipping',
            ])
            ->default('flat_rate_shipping_active');


        // Free Shipping Settings (Disabled)
        $this->divider('Free Shipping Settings');

        $this->text('free_shipping_min_order_amount', 'Minimum Order Amount for Free Shipping')
            ->rules('nullable|numeric')
            ->default($this->getSettingValue('free_shipping_min_order_amount'))
            ->help('Set the minimum order amount required to qualify for free shipping.')
            ->disable(); // Disable input

        // Flat Rate Shipping Settings (Enabled)
        $this->divider('Flat Rate Shipping Settings');

        $this->text('flat_rate_amount', 'Flat Rate Amount')
            ->rules('nullable|numeric')
            ->default($this->getSettingValue('flat_rate_amount'))
            ->help('Set the amount for flat rate shipping.');

        // Table Rate Shipping Settings (Disabled)
        $this->divider('Table Rate Shipping Settings');

        $this->textarea('table_rate_shipping', 'Table Rate Shipping Rules (JSON)')
            ->help('Define shipping rates based on weight and destination as a JSON array.')
            ->default($this->getSettingValue('table_rate_shipping'))
            ->rules('nullable|json')
            ->disable(); // Disable input

        // Shipping Carrier Settings (Disabled)
        $this->divider('Shipping Carrier Settings');

        $this->text('carrier_name', 'Carrier Name')
            ->rules('nullable|string')
            ->default($this->getSettingValue('carrier_name'))
            ->help('Enter the name of the shipping carrier.')
            ->disable(); // Disable input

        $this->text('carrier_api_key', 'Carrier API Key')
            ->rules('nullable|string')
            ->default($this->getSettingValue('carrier_api_key'))
            ->help('Enter the API key for the shipping carrier.')
            ->disable(); // Disable input

        // Product-Based Shipping Settings (Disabled)
        $this->divider('Product-Based Shipping Settings');

        $this->textarea('product_based_shipping', 'Product-Based Shipping Rules (JSON)')
            ->help('Define shipping rates for individual products as a JSON array.')
            ->default($this->getSettingValue('product_based_shipping'))
            ->rules('nullable|json')
            ->disable(); // Disable input

        // Disable reset button and change submit button text
        $this->disableReset();
    }

    public function data()
    {
        return [
            'free_shipping_min_order_amount' => $this->getSettingValue('free_shipping_min_order_amount'),
            'flat_rate_amount' => $this->getSettingValue('flat_rate_amount'),
            'table_rate_shipping' => $this->getSettingValue('table_rate_shipping'),
            'carrier_name' => $this->getSettingValue('carrier_name'),
            'carrier_api_key' => $this->getSettingValue('carrier_api_key'),
            'product_based_shipping' => $this->getSettingValue('product_based_shipping'),
        ];
    }

    protected function getSettingValue($key)
    {
        return Setting::where('key', $key)->first()?->value ?? '';
    }

    protected function getActiveShippingMethod()
    {
        // Fetch the active shipping method from the settings
        $methods = [
            'free_shipping_active',
            'flat_rate_shipping_active',
            'table_rate_shipping_active',
            'carrier_shipping_active',
            'product_based_shipping_active'
        ];

        foreach ($methods as $method) {
            if ($this->getSettingValue($method) === '1') {
                return $method;
            }
        }

        return null; // No active shipping method by default
    }
}
