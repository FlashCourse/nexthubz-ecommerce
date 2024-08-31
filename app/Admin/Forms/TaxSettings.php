<?php

namespace App\Admin\Forms;

use OpenAdmin\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Models\Setting;

class TaxSettings extends Form
{
    public $title = 'Tax Settings';

    public function handle(Request $request)
    {
        $data = $request->all();

        // Define a list of all switch keys
        $switchKeys = ['tax_by_country_active', 'tax_by_product_active'];

        // Set all switch fields to '0' (inactive) initially
        foreach ($switchKeys as $key) {
            $data[$key] = 0;
        }

        // Activate the switch fields based on input
        foreach ($switchKeys as $key) {
            if ($request->has("{$key}_cb")) {
                $data[$key] = 1;
            }
        }

        // Convert JSON fields to arrays
        $data['tax_by_country'] = json_encode($request->input('tax_by_country', ''));
        $data['tax_by_product'] = json_encode($request->input('tax_by_product', ''));

        // Update or create each setting based on processed data
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        admin_toastr('Saved Successfully', 'success');

        return back();
    }

    public function form()
    {
        // Default Tax Settings (Enabled)
        $this->divider('Default Tax Settings');
        $this->text('default_tax_rate', 'Default Tax Rate')
            ->rules('required')
            ->help('Enter the default tax rate (e.g., 15 for 15%)')
            ->default($this->getSettingValue('default_tax_rate'));

        $this->textarea('tax_inclusive_message', 'Tax Inclusive Message')
            ->help('Message to display when prices include tax')
            ->default($this->getSettingValue('tax_inclusive_message'));

        $this->textarea('tax_exclusive_message', 'Tax Exclusive Message')
            ->help('Message to display when prices exclude tax')
            ->default($this->getSettingValue('tax_exclusive_message'));

        // Tax by Country/Region (Disabled)
        $this->divider('Tax by Country/Region');
        $this->switch('tax_by_country_active', 'Activate Tax by Country/Region')
            ->help('Enable or disable tax settings by country/region.')
            ->default((int) $this->getSettingValue('tax_by_country_active')) // Cast to int for boolean display
            ->disable(); // Disable input

        $this->textarea('tax_by_country', 'Tax Rates by Country/Region')
            ->help('Define tax rates for different countries and states/regions in JSON format.')
            ->default($this->getSettingValue('tax_by_country'))
            ->disable(); // Disable input

        // Tax by Product (Disabled)
        $this->divider('Tax by Product');
        $this->switch('tax_by_product_active', 'Activate Tax by Product')
            ->help('Enable or disable tax settings by product.')
            ->default((int) $this->getSettingValue('tax_by_product_active')) // Cast to int for boolean display
            ->disable(); // Disable input

        $this->textarea('tax_by_product', 'Tax Rates by Product')
            ->help('Set specific tax rates for individual products in JSON format.')
            ->default($this->getSettingValue('tax_by_product'))
            ->disable(); // Disable input

        // Disable reset button and change submit button text
        $this->disableReset();
    }

    public function data()
    {
        return [
            'default_tax_rate' => $this->getSettingValue('default_tax_rate'),
            'tax_inclusive_message' => $this->getSettingValue('tax_inclusive_message'),
            'tax_exclusive_message' => $this->getSettingValue('tax_exclusive_message'),
            'tax_by_country_active' => (int) $this->getSettingValue('tax_by_country_active'), // Cast to int for boolean display
            'tax_by_country' => $this->getSettingValue('tax_by_country'),
            'tax_by_product_active' => (int) $this->getSettingValue('tax_by_product_active'), // Cast to int for boolean display
            'tax_by_product' => $this->getSettingValue('tax_by_product'),
        ];
    }

    protected function getSettingValue($key)
    {
        return Setting::where('key', $key)->first()?->value ?? '';
    }
}
