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

        // Convert JSON fields to arrays
        $data['table_rate_shipping'] = json_encode($data['table_rate_shipping'] ?? []);
        $data['product_based_shipping'] = json_encode($data['product_based_shipping'] ?? []);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        admin_toastr('Settings saved successfully!', 'success');

        return back();
    }

    public function form()
    {

        // Free Shipping Settings
        $this->divider('Free Shipping Settings');

        $this->switch('free_shipping_active', 'Activate Free Shipping')
            ->default($this->getSettingValue('free_shipping_active') === 'true')
            ->help('Enable or disable free shipping.');

        $this->text('free_shipping_min_order_amount', 'Minimum Order Amount for Free Shipping')
            ->rules('nullable|numeric')
            ->default($this->getSettingValue('free_shipping_min_order_amount'))
            ->help('Set the minimum order amount required to qualify for free shipping.');

        // Flat Rate Shipping Settings
        $this->divider('Flat Rate Shipping Settings');

        $this->switch('flat_rate_shipping_active', 'Activate Flat Rate Shipping')
            ->default($this->getSettingValue('flat_rate_shipping_active') === 'true')
            ->help('Enable or disable flat rate shipping.');

        $this->text('flat_rate_amount', 'Flat Rate Amount')
            ->rules('nullable|numeric')
            ->default($this->getSettingValue('flat_rate_amount'))
            ->help('Set the amount for flat rate shipping.');

        // Table Rate Shipping Settings
        $this->divider('Table Rate Shipping Settings');

        $this->switch('table_rate_shipping_active', 'Activate Table Rate Shipping')
            ->default($this->getSettingValue('table_rate_shipping_active') === 'true')
            ->help('Enable or disable table rate shipping.');

        $this->textarea('table_rate_shipping', 'Table Rate Shipping Rules (JSON)')
            ->help('Define shipping rates based on weight and destination as a JSON array.')
            ->default($this->getSettingValue('table_rate_shipping'))
            ->rules('nullable|json');

        // Shipping Carrier Settings
        $this->divider('Shipping Carrier Settings');

        $this->switch('carrier_shipping_active', 'Activate Carrier Shipping')
            ->default($this->getSettingValue('carrier_shipping_active') === 'true')
            ->help('Enable or disable shipping via carrier.');

        $this->text('carrier_name', 'Carrier Name')
            ->rules('nullable|string')
            ->default($this->getSettingValue('carrier_name'))
            ->help('Enter the name of the shipping carrier.');

        $this->text('carrier_api_key', 'Carrier API Key')
            ->rules('nullable|string')
            ->default($this->getSettingValue('carrier_api_key'))
            ->help('Enter the API key for the shipping carrier.');

        // Product-Based Shipping Settings
        $this->divider('Product-Based Shipping Settings');

        $this->switch('product_based_shipping_active', 'Activate Product-Based Shipping')
            ->default($this->getSettingValue('product_based_shipping_active') === 'true')
            ->help('Enable or disable product-based shipping.');

        $this->textarea('product_based_shipping', 'Product-Based Shipping Rules (JSON)')
            ->help('Define shipping rates for individual products as a JSON array.')
            ->default($this->getSettingValue('product_based_shipping'))
            ->rules('nullable|json');

        // Disable reset button and change submit button text
        $this->disableReset();
    }

    public function data()
    {
        return [
            'shipping_settings_active' => $this->getSettingValue('shipping_settings_active') === 'true',
            'free_shipping_active' => $this->getSettingValue('free_shipping_active') === 'true',
            'free_shipping_min_order_amount' => $this->getSettingValue('free_shipping_min_order_amount'),
            'flat_rate_shipping_active' => $this->getSettingValue('flat_rate_shipping_active') === 'true',
            'flat_rate_amount' => $this->getSettingValue('flat_rate_amount'),
            'table_rate_shipping_active' => $this->getSettingValue('table_rate_shipping_active') === 'true',
            'table_rate_shipping' => $this->getSettingValue('table_rate_shipping'),
            'carrier_shipping_active' => $this->getSettingValue('carrier_shipping_active') === 'true',
            'carrier_name' => $this->getSettingValue('carrier_name'),
            'carrier_api_key' => $this->getSettingValue('carrier_api_key'),
            'product_based_shipping_active' => $this->getSettingValue('product_based_shipping_active') === 'true',
            'product_based_shipping' => $this->getSettingValue('product_based_shipping'),
        ];
    }

    protected function getSettingValue($key)
    {
        return Setting::where('key', $key)->first()?->value ?? '';
    }
}
