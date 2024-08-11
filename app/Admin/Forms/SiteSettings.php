<?php

namespace App\Admin\Forms;

use OpenAdmin\Admin\Widgets\Form;
use Illuminate\Http\Request;
use App\Models\Setting;

class SiteSettings extends Form
{
    public $title = 'Site Settings';

    public function handle(Request $request)
    {
        $data = $request->all();

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        admin_toastr('Site settings updated successfully!', 'success');

        return back();
    }

    public function form()
    {
        // Home Page Settings
        $this->divider('Home Page Settings');
        $this->switch('home_page_active', 'Activate Home Page')
            ->default($this->getSettingValue('home_page_active') === 'true');

        $this->text('home_page_title', 'Home Page Title')
            ->default($this->getSettingValue('home_page_title'));

        $this->textarea('home_page_description', 'Home Page Description')
            ->default($this->getSettingValue('home_page_description'));

        $this->image('home_page_banner', 'Home Page Banner Image')
            ->default($this->getSettingValue('home_page_banner'));

        // About Page Settings
        $this->divider('About Page Settings');
        $this->switch('about_page_active', 'Activate About Page')
            ->default($this->getSettingValue('about_page_active') === 'true');

        $this->text('about_page_title', 'About Page Title')
            ->default($this->getSettingValue('about_page_title'));

        $this->textarea('about_page_content', 'About Page Content')
            ->default($this->getSettingValue('about_page_content'));

        $this->image('about_page_banner', 'About Page Banner Image')
            ->default($this->getSettingValue('about_page_banner'));

        // Contact Page Settings
        $this->divider('Contact Page Settings');
        $this->switch('contact_page_active', 'Activate Contact Page')
            ->default($this->getSettingValue('contact_page_active') === 'true');

        $this->text('contact_page_title', 'Contact Page Title')
            ->default($this->getSettingValue('contact_page_title'));

        $this->textarea('contact_page_content', 'Contact Page Content')
            ->default($this->getSettingValue('contact_page_content'));

        $this->text('contact_page_email', 'Contact Email')
            ->default($this->getSettingValue('contact_page_email'));

        $this->text('contact_page_phone', 'Contact Phone')
            ->default($this->getSettingValue('contact_page_phone'));

        // FAQ Page Settings
        $this->divider('FAQ Page Settings');
        $this->switch('faq_page_active', 'Activate FAQ Page')
            ->default($this->getSettingValue('faq_page_active') === 'true');

        $this->text('faq_page_title', 'FAQ Page Title')
            ->default($this->getSettingValue('faq_page_title'));

        $this->textarea('faq_page_content', 'FAQ Page Content')
            ->default($this->getSettingValue('faq_page_content'));

        // Blog Page Settings
        $this->divider('Blog Page Settings');
        $this->switch('blog_page_active', 'Activate Blog Page')
            ->default($this->getSettingValue('blog_page_active') === 'true');

        $this->text('blog_page_title', 'Blog Page Title')
            ->default($this->getSettingValue('blog_page_title'));

        $this->textarea('blog_page_description', 'Blog Page Description')
            ->default($this->getSettingValue('blog_page_description'));

        // Disable reset button and change submit button text
        $this->disableReset();
    }

    protected function getSettingValue($key)
    {
        return Setting::where('key', $key)->first()?->value ?? '';
    }
}
