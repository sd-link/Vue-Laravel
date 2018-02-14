<?php

use Illuminate\Database\Seeder;

class BrightThemeCssSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SiteSetting::set('Admin.Layout', 'theme', 'bright');

        // Main Menu
        SiteSetting::set('Admin.Colors', 'main_menu_font_size', '13');
        SiteSetting::set('Admin.Colors', 'main_menu_font_color', '#b8c7ce');
        SiteSetting::set('Admin.Colors', 'main_menu_font_hover_color', '#ffffff');
        SiteSetting::set('Admin.Colors', 'main_menu_main_background_color', '#222d32');
        SiteSetting::set('Admin.Colors', 'main_menu_sub_background_color', '#2c3b41');
        SiteSetting::set('Admin.Colors', 'main_menu_menu_hover_color', '#1e282c');

        // Content
        SiteSetting::set('Admin.Colors', 'content_font_color', '#b8c7ce');
        SiteSetting::set('Admin.Colors', 'content_background_color', '#2F3A40');
        SiteSetting::set('Admin.Colors', 'content_container_background_color', 'rgba(255,255,255,0.45)');

        SiteSetting::set('Admin.Colors', 'content_table_thead_background_color', 'rgba(255,255,255, 0.03)');
        SiteSetting::set('Admin.Colors', 'content_table_tbody_tr_even_background_color', 'rgba(255,255,255, 0.02)');
        SiteSetting::set('Admin.Colors', 'content_table_tbody_tr_odd_background_color', 'rgba(255,255,255, 0.005)');
        SiteSetting::set('Admin.Colors', 'content_table_tbody_tr_hover_background_color', 'rgba(255,255,255, 0.035)');

        // Box
        SiteSetting::set('Admin.Colors', 'content_box_background_color', '#222D32');
        SiteSetting::set('Admin.Colors', 'content_box_header_footer_border_color', 'rgba(9,30,39,0.44)');

        // Tabs
        SiteSetting::set('Admin.Colors', 'content_tabs_menu_background_color', '#2a333a');
        SiteSetting::set('Admin.Colors', 'content_tabs_background_color', '#222d32');
        SiteSetting::set('Admin.Colors', 'content_tabs_top_border_color', 'rgba(0,0,0,0)');
        SiteSetting::set('Admin.Colors', 'content_tabs_sides_border_color', 'rgba(83,128,187,0.3)');

        // Input
        SiteSetting::set('Admin.Colors', 'content_input_font_color', 'rgba(159,194,213,0.7)');
        SiteSetting::set('Admin.Colors', 'content_input_background_color', '#2e3b40');
        SiteSetting::set('Admin.Colors', 'content_input_border_color', 'rgba(17,17,17,0.58)');
        SiteSetting::set('Admin.Colors', 'content_input_addon_font_color', '#b8c7ce');
        SiteSetting::set('Admin.Colors', 'content_input_addon_background_color', '#1e292e');
        SiteSetting::set('Admin.Colors', 'content_input_addon_border_color', 'rgba(17,17,17,0.32)');

        $custom_css_markup = file_get_contents(public_path().'/themes/Admin_Theme/css/custom_markup/custom_css_markup.css');
        $admin_colors = SiteSetting::getSection('Admin.Colors');

        foreach ($admin_colors as $key => $value) {
            $custom_css_markup = str_replace('{'.$key.'}', $value, $custom_css_markup);
        }

        file_put_contents(public_path().'/themes/Admin_Theme/css/custom-css.css', $custom_css_markup);
    }
}
