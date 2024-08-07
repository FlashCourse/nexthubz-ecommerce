<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\GeneralSetting;
use App\Admin\Forms\SeoSetting;
use OpenAdmin\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class SettingController extends Controller
{
    public function index(Content $content)
    {
        // Instantiate the form objects
        $generalSettingForm = new GeneralSetting();
        $seoSettingForm = new SeoSetting();

        return $content
            ->title('Settings')
            ->row($generalSettingForm->render())
            ->row($seoSettingForm->render());
    }
}
