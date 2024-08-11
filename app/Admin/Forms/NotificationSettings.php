<?php

namespace App\Admin\Forms;

use OpenAdmin\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Models\Setting;

class NotificationSettings extends Form
{
    public $title = 'Notification Settings';

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
        // Email Notifications
        $this->divider('Email Notifications');

        $this->switch('email_notifications', 'Enable Email Notifications')
            ->options($this->getBooleanStates())
            ->help('Enable or disable email notifications')
            ->default($this->getSettingValue('email_notifications'));

        $this->text('email_from_address', 'Email From Address')
            ->help('Enter the email address from which notifications will be sent')
            ->default($this->getSettingValue('email_from_address'));

        $this->text('email_from_name', 'Email From Name')
            ->help('Enter the name to display in email notifications')
            ->default($this->getSettingValue('email_from_name'));

        $this->text('email_smtp_host', 'SMTP Host')
            ->help('Enter the SMTP host for sending email notifications')
            ->default($this->getSettingValue('email_smtp_host'));

        $this->text('email_smtp_port', 'SMTP Port')
            ->help('Enter the SMTP port for sending email notifications')
            ->default($this->getSettingValue('email_smtp_port'));

        $this->text('email_smtp_username', 'SMTP Username')
            ->help('Enter the SMTP username for sending email notifications')
            ->default($this->getSettingValue('email_smtp_username'));

        $this->text('email_smtp_password', 'SMTP Password')
            ->help('Enter the SMTP password for sending email notifications')
            ->default($this->getSettingValue('email_smtp_password'));

        // SMS Notifications
        $this->divider('SMS Notifications');

        $this->switch('sms_notifications', 'Enable SMS Notifications')
            ->options($this->getBooleanStates())
            ->help('Enable or disable SMS notifications')
            ->default($this->getSettingValue('sms_notifications'));

        $this->text('sms_provider', 'SMS Provider')
            ->help('Enter the name of your SMS provider')
            ->default($this->getSettingValue('sms_provider'));

        $this->text('sms_api_key', 'SMS API Key')
            ->help('Enter the API key for your SMS provider')
            ->default($this->getSettingValue('sms_api_key'));

        // Push Notifications
        $this->divider('Push Notifications');

        $this->switch('push_notifications', 'Enable Push Notifications')
            ->options($this->getBooleanStates())
            ->help('Enable or disable push notifications')
            ->default($this->getSettingValue('push_notifications'));

        $this->text('push_notification_service', 'Push Notification Service')
            ->help('Enter the name of your push notification service provider')
            ->default($this->getSettingValue('push_notification_service'));

        // Notification Message Template
        $this->divider('Notification Message Template');

        $this->textarea('notification_message_template', 'Notification Message Template')
            ->help('Enter the template for notification messages')
            ->default($this->getSettingValue('notification_message_template'));

        // Disable reset button and change submit button text
        $this->disableReset();
    }

    public function data()
    {
        return [
            'email_notifications' => $this->getSettingValue('email_notifications'),
            'email_from_address' => $this->getSettingValue('email_from_address'),
            'email_from_name' => $this->getSettingValue('email_from_name'),
            'email_smtp_host' => $this->getSettingValue('email_smtp_host'),
            'email_smtp_port' => $this->getSettingValue('email_smtp_port'),
            'email_smtp_username' => $this->getSettingValue('email_smtp_username'),
            'email_smtp_password' => $this->getSettingValue('email_smtp_password'),
            'sms_notifications' => $this->getSettingValue('sms_notifications'),
            'sms_provider' => $this->getSettingValue('sms_provider'),
            'sms_api_key' => $this->getSettingValue('sms_api_key'),
            'push_notifications' => $this->getSettingValue('push_notifications'),
            'push_notification_service' => $this->getSettingValue('push_notification_service'),
            'notification_message_template' => $this->getSettingValue('notification_message_template'),
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
