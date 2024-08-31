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

        // Define a list of all switch keys
        $switchKeys = [
            'home_page_active',
            'about_page_active',
            'contact_page_active',
            'faq_page_active',
            'blog_page_active'
        ];

        // Set all switch fields to '0' (inactive) initially
        foreach ($switchKeys as $key) {
            $data[$key] = 0;
        }

        // Check if switch was toggled on and update accordingly
        foreach ($switchKeys as $key) {
            if ($request->has("{$key}_cb")) {
                $data[$key] = 1;
            }
        }

        // Update or create each setting based on processed data
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        admin_toastr('Site settings updated successfully!', 'success');

        return back();
    }

    public function form()
    {
        // Home Page Settings (All Disabled)
        $this->divider('Home Page Settings');
        $this->switch('home_page_active', 'Activate Home Page')
            ->default((int)$this->getSettingValue('home_page_active')) // Cast to int for boolean display
            ->disable(); // Disable input

        $this->text('home_page_title', 'Home Page Title')
            ->default($this->getSettingValue('home_page_title'))
            ->disable(); // Disable input

        $this->textarea('home_page_description', 'Home Page Description')
            ->default($this->getSettingValue('home_page_description'))
            ->disable(); // Disable input

        $this->image('home_page_banner', 'Home Page Banner Image')
            ->default($this->getSettingValue('home_page_banner'))
            ->disable(); // Disable input

        // About Page Settings (All Disabled)
        $this->divider('About Page Settings');
        $this->switch('about_page_active', 'Activate About Page')
            ->default((int)$this->getSettingValue('about_page_active')) // Cast to int for boolean display
            ->disable(); // Disable input

        $this->text('about_page_title', 'About Page Title')
            ->default($this->getSettingValue('about_page_title'))
            ->disable(); // Disable input

        $this->textarea('about_page_content', 'About Page Content')
            ->default($this->getSettingValue('about_page_content'))
            ->disable(); // Disable input

        $this->image('about_page_banner', 'About Page Banner Image')
            ->default($this->getSettingValue('about_page_banner'))
            ->disable(); // Disable input

        // Contact Page Settings (All Disabled)
        $this->divider('Contact Page Settings');
        $this->switch('contact_page_active', 'Activate Contact Page')
            ->default((int)$this->getSettingValue('contact_page_active')) // Cast to int for boolean display
            ->disable(); // Disable input

        $this->text('contact_page_title', 'Contact Page Title')
            ->default($this->getSettingValue('contact_page_title'))
            ->disable(); // Disable input

        $this->textarea('contact_page_content', 'Contact Page Content')
            ->default($this->getSettingValue('contact_page_content'))
            ->disable(); // Disable input

        $this->text('contact_page_email', 'Contact Email')
            ->default($this->getSettingValue('contact_page_email'))
            ->disable(); // Disable input

        $this->text('contact_page_phone', 'Contact Phone')
            ->default($this->getSettingValue('contact_page_phone'))
            ->disable(); // Disable input

        // FAQ Page Settings (All Disabled)
        $this->divider('FAQ Page Settings');
        $this->switch('faq_page_active', 'Activate FAQ Page')
            ->default((int)$this->getSettingValue('faq_page_active')) // Cast to int for boolean display
            ->disable(); // Disable input

        $this->text('faq_page_title', 'FAQ Page Title')
            ->default($this->getSettingValue('faq_page_title'))
            ->disable(); // Disable input

        $this->textarea('faq_page_content', 'FAQ Page Content')
            ->default($this->getSettingValue('faq_page_content'))
            ->disable(); // Disable input

        // Blog Page Settings (All Disabled)
        $this->divider('Blog Page Settings');
        $this->switch('blog_page_active', 'Activate Blog Page')
            ->default((int)$this->getSettingValue('blog_page_active')) // Cast to int for boolean display
            ->disable(); // Disable input

        $this->text('blog_page_title', 'Blog Page Title')
            ->default($this->getSettingValue('blog_page_title'))
            ->disable(); // Disable input

        $this->textarea('blog_page_description', 'Blog Page Description')
            ->default($this->getSettingValue('blog_page_description'))
            ->disable(); // Disable input

        // Disable reset button and change submit button text
        $this->disableReset();
    }

    protected function getSettingValue($key)
    {
        return Setting::where('key', $key)->first()?->value ?? '';
    }
}
