<?php

namespace App\Admin\Forms;

use OpenAdmin\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Models\Setting;

class GeneralSetting extends Form
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
        $this->text('site_name', 'Site Name')->rules('required')->default($this->getSettingValue('site_name'));
        $this->image('site_logo', 'Site Logo')->default($this->getSettingValue('site_logo'));

        // Disable reset button and change submit button text
        $this->disableReset();
    }



    public function data()
    {
        return [
            'site_name' => $this->getSettingValue('site_name'),
            'site_logo' => $this->getSettingValue('site_logo'),
        ];
    }

    protected function getSettingValue($key)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : null;
    }
}
