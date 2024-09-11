<?php

namespace App\Admin\Forms;

use OpenAdmin\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Models\Setting;

class CompanySettings extends Form
{
    public $title = 'Company Settings';

    public function handle(Request $request)
    {
        $data = $request->all();

        // Update or create each setting based on processed data
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        admin_toastr('Company settings saved successfully!', 'success');

        return back();
    }

    public function form()
    {
        // General Company Information
        $this->divider('General Company Information');

        $this->text('company_name', 'Company Name')
            ->rules('required|string')
            ->default($this->getSettingValue('company_name'))
            ->help('Enter the official name of the company.');

        $this->textarea('company_overview', 'Company Overview')
            ->rules('nullable|string')
            ->default($this->getSettingValue('company_overview'))
            ->help('Provide a brief overview of the company.');

        $this->text('founding_year', 'Founding Year')
            ->rules('nullable|numeric')
            ->default($this->getSettingValue('founding_year'))
            ->help('Enter the year the company was founded.');

        $this->text('ceo_name', 'CEO Name')
            ->rules('nullable|string')
            ->default($this->getSettingValue('ceo_name'))
            ->help('Enter the name of the company’s CEO.');

        // Contact Information
        $this->divider('Contact Information');

        $this->text('operations_hq', 'Operations HQ Address')
            ->rules('required|string')
            ->default($this->getSettingValue('operations_hq'))
            ->help('Enter the Operations HQ address.');

        $this->text('corporate', 'Corporate Office Address')
            ->rules('required|string')
            ->default($this->getSettingValue('corporate'))
            ->help('Enter the Corporate Office address.');

        $this->text('phone_1', 'Primary Phone Number')
            ->rules('required|string')
            ->default($this->getSettingValue('phone_1'))
            ->help('Enter the primary contact phone number.');

        $this->text('phone_2', 'Secondary Phone Number')
            ->rules('nullable|string')
            ->default($this->getSettingValue('phone_2'))
            ->help('Enter the secondary contact phone number (optional).');

        $this->text('hotline', 'Hotline Number')
            ->rules('required|string')
            ->default($this->getSettingValue('hotline'))
            ->help('Enter the hotline number for customer support.');

        $this->email('email', 'Company Email Address')
            ->rules('required|email')
            ->default($this->getSettingValue('email'))
            ->help('Enter the main email address for the company.');

        $this->text('fax_number', 'Fax Number')
            ->rules('nullable|string')
            ->default($this->getSettingValue('fax_number'))
            ->help('Enter the company fax number (if applicable).');

        $this->text('skype_telegram_whatsapp', 'Skype, Telegram, WhatsApp')
            ->rules('nullable|string')
            ->default($this->getSettingValue('skype_telegram_whatsapp'))
            ->help('Enter Skype, Telegram, and WhatsApp contact details.');

        $this->textarea('get_in_touch', 'Get in Touch Message')
            ->rules('nullable|string')
            ->default($this->getSettingValue('get_in_touch'))
            ->help('Enter a message for the "Get in Touch" section.');

        // Working Hours
        $this->divider('Working Hours');

        $this->text('working_hours_weekdays', 'Weekdays Working Hours')
            ->rules('nullable|string')
            ->default($this->getSettingValue('working_hours_weekdays'))
            ->help('Enter the working hours for weekdays.');

        $this->text('working_hours_weekends', 'Weekends Working Hours')
            ->rules('nullable|string')
            ->default($this->getSettingValue('working_hours_weekends'))
            ->help('Enter the working hours for weekends.');

        // Company Mission and Values
        $this->divider('Company Mission and Values');

        $this->textarea('mission_statement', 'Mission Statement')
            ->rules('nullable|string')
            ->default($this->getSettingValue('mission_statement'))
            ->help('Enter the company’s mission statement.');

        $this->textarea('values', 'Company Values')
            ->rules('nullable|string')
            ->default($this->getSettingValue('values'))
            ->help('Enter the core values of the company.');

        // Additional Information
        $this->divider('Additional Information');

        $this->text('number_of_employees', 'Number of Employees')
            ->rules('nullable|numeric')
            ->default($this->getSettingValue('number_of_employees'))
            ->help('Enter the total number of employees in the company.');

        $this->text('customer_support_hours', 'Customer Support Hours')
            ->rules('nullable|string')
            ->default($this->getSettingValue('customer_support_hours'))
            ->help('Enter the hours during which customer support is available.');

        // Disable reset button and customize submit button text
        $this->disableReset();
    }

    public function data()
    {
        return [
            'company_name' => $this->getSettingValue('company_name'),
            'company_overview' => $this->getSettingValue('company_overview'),
            'founding_year' => $this->getSettingValue('founding_year'),
            'ceo_name' => $this->getSettingValue('ceo_name'),
            'operations_hq' => $this->getSettingValue('operations_hq'),
            'corporate' => $this->getSettingValue('corporate'),
            'phone_1' => $this->getSettingValue('phone_1'),
            'phone_2' => $this->getSettingValue('phone_2'),
            'hotline' => $this->getSettingValue('hotline'),
            'email' => $this->getSettingValue('email'),
            'fax_number' => $this->getSettingValue('fax_number'),
            'skype_telegram_whatsapp' => $this->getSettingValue('skype_telegram_whatsapp'),
            'get_in_touch' => $this->getSettingValue('get_in_touch'),
            'working_hours_weekdays' => $this->getSettingValue('working_hours_weekdays'),
            'working_hours_weekends' => $this->getSettingValue('working_hours_weekends'),
            'mission_statement' => $this->getSettingValue('mission_statement'),
            'values' => $this->getSettingValue('values'),
            'number_of_employees' => $this->getSettingValue('number_of_employees'),
            'customer_support_hours' => $this->getSettingValue('customer_support_hours'),
        ];
    }

    protected function getSettingValue($key)
    {
        return Setting::where('key', $key)->first()?->value ?? '';
    }
}
