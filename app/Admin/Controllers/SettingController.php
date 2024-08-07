<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\GeneralSettings;
use OpenAdmin\Admin\Layout\Content;
use Illuminate\Routing\Controller;

class SettingController extends Controller
{
    public function general(Content $content)
    {
        // Instantiate the form objects
        $generalSettingForm = new GeneralSettings();

        return $content
            ->title('Settings')
            ->row($generalSettingForm->render());
    }
    public function site(Content $content)
    {
    }

    public function shipping(Content $content)
    {
    }

    public function payment(Content $content)
    {
    }

    public function tax(Content $content)
    {
    }

    public function notification(Content $content)
    {
    }

    public function api(Content $content)
    {
    }
}
