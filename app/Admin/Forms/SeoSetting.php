<?php

namespace App\Admin\Forms;

use OpenAdmin\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Models\Setting;

class SeoSetting extends Form
{
    public $title = 'SEO Settings';

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
        $this->text('meta_title', 'Meta Title')->rules('required')->default($this->getSettingValue('meta_title'));
        $this->textarea('meta_description', 'Meta Description')->default($this->getSettingValue('meta_description'));
        $this->textarea('meta_keywords', 'Meta Keywords')->default($this->getSettingValue('meta_keywords'));

        // Disable reset button and change submit button text
        $this->disableReset();
    }

    public function data()
    {
        return [
            'meta_title'       => $this->getSettingValue('meta_title'),
            'meta_description' => $this->getSettingValue('meta_description'),
            'meta_keywords'    => $this->getSettingValue('meta_keywords'),
        ];
    }

    protected function getSettingValue($key)
    {
        $setting = Setting::where('key', $key)->first();
        return $setting ? $setting->value : null;
    }
}
