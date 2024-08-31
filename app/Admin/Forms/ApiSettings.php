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

        // Define a list of all switch keys
        $switchKeys = ['api_enabled'];

        // Set all switch fields to '0' (inactive) initially
        foreach ($switchKeys as $key) {
            $data[$key] = 0;
        }

        // Activate the switch field based on input
        foreach ($switchKeys as $key) {
            if ($request->has("{$key}_cb")) {
                $data[$key] = 1;
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
        // API Settings
        $this->divider('API Settings');

        $this->text('api_key', 'API Key')
            ->help('Enter your API key here')
            ->default($this->getSettingValue('api_key'))
            ->disable(); // Disable input

        $this->text('api_secret', 'API Secret')
            ->help('Enter your API secret here')
            ->default($this->getSettingValue('api_secret'))
            ->disable(); // Disable input

        $this->text('api_base_url', 'API Base URL')
            ->help('Enter the base URL for your API')
            ->default($this->getSettingValue('api_base_url'))
            ->disable(); // Disable input

        $this->number('api_timeout', 'API Timeout')
            ->help('Set the timeout for API requests in seconds')
            ->default($this->getSettingValue('api_timeout'))
            ->disable(); // Disable input

        $this->switch('api_enabled', 'Enable API')
            ->options($this->getBooleanStates())
            ->help('Enable or disable API access')
            ->default((int) $this->getSettingValue('api_enabled')) // Cast to int for boolean display
            ->disable(); // Disable input

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
            'api_enabled' => (int) $this->getSettingValue('api_enabled'), // Cast to int for boolean display
        ];
    }

    protected function getSettingValue($key)
    {
        return Setting::where('key', $key)->first()?->value ?? '';
    }

    protected function getBooleanStates()
    {
        return [
            1 => 'Enabled',
            0 => 'Disabled',
        ];
    }
}
