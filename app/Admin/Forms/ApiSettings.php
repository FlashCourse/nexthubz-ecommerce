<?php

namespace App\Admin\Forms;

use OpenAdmin\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Models\Setting;

class ApiSettings extends Form
{
    public $title = 'API Settings';

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
        // API Settings
        $this->divider('API Settings');
        $this->text('api_key', 'API Key')
            ->help('Enter your API key here')
            ->default($this->getSettingValue('api_key'));

        $this->text('api_secret', 'API Secret')
            ->help('Enter your API secret here')
            ->default($this->getSettingValue('api_secret'));

        $this->text('api_base_url', 'API Base URL')
            ->help('Enter the base URL for your API')
            ->default($this->getSettingValue('api_base_url'));

        $this->number('api_timeout', 'API Timeout')
            ->help('Set the timeout for API requests in seconds')
            ->default($this->getSettingValue('api_timeout'));

        $this->switch('api_enabled', 'Enable API')
            ->options($this->getBooleanStates())
            ->help('Enable or disable API access')
            ->default($this->getSettingValue('api_enabled'));

        // Add any additional API-related settings here

        // Disable reset button and change submit button text
        $this->disableReset();
    }

    public function data()
    {
        return [
            'api_key' => $this->getSettingValue('api_key'),
            'api_secret' => $this->getSettingValue('api_secret'),
            'api_base_url' => $this->getSettingValue('api_base_url'),
            'api_timeout' => $this->getSettingValue('api_timeout'),
            'api_enabled' => $this->getSettingValue('api_enabled'),
        ];
    }

    protected function getSettingValue($key)
    {
        return Setting::where('key', $key)->first()?->value;
    }

    protected function getBooleanStates()
    {
        return [
            1 => 'Enabled',
            0 => 'Disabled',
        ];
    }
}
