<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\ApiSettings;
use App\Admin\Forms\GeneralSettings;
use App\Admin\Forms\NotificationSettings;
use App\Admin\Forms\PaymentSettings;
use App\Admin\Forms\ShippingSettings;
use App\Admin\Forms\SiteSettings;
use App\Admin\Forms\TaxSettings;
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
        // Instantiate the form objects
        $siteSettingForm = new SiteSettings();

        return $content
            ->title('Settings')
            ->row($siteSettingForm->render());
    }

    public function shipping(Content $content)
    {
        // Instantiate the form objects
        $shippingSettingForm = new ShippingSettings();

        return $content
            ->title('Settings')
            ->row($shippingSettingForm->render());
    }

    public function payment(Content $content)
    {
        // Instantiate the form objects
        $paymentSettingForm = new PaymentSettings();

        return $content
            ->title('Settings')
            ->row($paymentSettingForm->render());
    }

    public function tax(Content $content)
    {
        // Instantiate the form objects
        $taxSettingForm = new TaxSettings();

        return $content
            ->title('Settings')
            ->row($taxSettingForm->render());
    }

    public function notification(Content $content)
    {
        // Instantiate the form objects
        $notificationSettingForm = new NotificationSettings();

        return $content
            ->title('Settings')
            ->row($notificationSettingForm->render());
    }

    public function api(Content $content)
    {
        // Instantiate the form objects
        $apiSettingForm = new ApiSettings();

        return $content
            ->title('Settings')
            ->row($apiSettingForm->render());
    }
}
