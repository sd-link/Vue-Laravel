<?php

namespace App\Models\Core\Settings;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    static public function configure()
    {
        $custom_css_markup = file_get_contents(public_path().'/themes/Admin_Theme/css/custom_markup/custom_css_markup.css');
        $admin_colors = Setting::getSection('Admin.Colors');
        $admin_content = Setting::getSection('Admin.Content');

        foreach ($admin_colors as $key => $value) {
            $custom_css_markup = str_replace('{'.$key.'}', $value, $custom_css_markup);
        }

        foreach ($admin_content as $key => $value) {
            $custom_css_markup = str_replace('{'.$key.'}', $value, $custom_css_markup);
        }

        file_put_contents(public_path().'/themes/Admin_Theme/css/custom-css.css', $custom_css_markup);
    }
}
