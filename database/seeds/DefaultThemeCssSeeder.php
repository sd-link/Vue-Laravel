<?php

use Illuminate\Database\Seeder;

class DefaultThemeCssSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Main Menu
        SiteSetting::set('Admin.Colors', 'main_menu_font_size', '13');
        SiteSetting::set('Admin.Colors', 'main_menu_font_color', '#b8c7ce');
        SiteSetting::set('Admin.Colors', 'main_menu_font_hover_color', '#ffffff');
        SiteSetting::set('Admin.Colors', 'main_menu_main_background_color', '#222d32');
        SiteSetting::set('Admin.Colors', 'main_menu_sub_background_color', '#2c3b41');
        SiteSetting::set('Admin.Colors', 'main_menu_menu_hover_color', '#1e282c');

        // Content
        SiteSetting::set('Admin.Colors', 'content_font_color', '#333');
        SiteSetting::set('Admin.Colors', 'content_background_color', '#F0F1F3');
        SiteSetting::set('Admin.Colors', 'content_container_background_color', 'rgba(255,255,255,0.45)');

        // Content Rows&Columns
        SiteSetting::set('Admin.Colors', 'content_table_thead_background_color', 'rgba(0,0,0, 0.04)');
        SiteSetting::set('Admin.Colors', 'content_table_tbody_tr_even_background_color', 'rgba(247, 246, 245, 0.5)');
        SiteSetting::set('Admin.Colors', 'content_table_tbody_tr_odd_background_color', 'rgba(247, 246, 245, 0.25)');
        SiteSetting::set('Admin.Colors', 'content_table_tbody_tr_hover_background_color', 'rgba(0,0,0, 0.035)');

        SiteSetting::set('Admin.Colors', 'content_row_header_background_color', 'rgba(247, 246, 245, 0.7)');
        SiteSetting::set('Admin.Colors', 'content_row_even_background_color', 'rgba(247, 246, 245, 0.35)');
        SiteSetting::set('Admin.Colors', 'content_row_odd_background_color', 'rgba(247, 246, 245, 0.02)');
        SiteSetting::set('Admin.Colors', 'content_row_hover_background_color', 'rgba(232, 232, 232, 0.2)');

        // Box
        SiteSetting::set('Admin.Colors', 'content_box_background_color', '#ffffff');
        SiteSetting::set('Admin.Colors', 'content_box_header_footer_border_color', '#f4f4f4');

        // Tabs
        SiteSetting::set('Admin.Colors', 'content_tabs_menu_background_color', '#fff');
        SiteSetting::set('Admin.Colors', 'content_tabs_menu_bottom_border_color', '#f4f4f4');
        SiteSetting::set('Admin.Colors', 'content_tabs_background_color', '#fff');
        SiteSetting::set('Admin.Colors', 'content_tabs_top_border_color', '#3c8dbc');
        SiteSetting::set('Admin.Colors', 'content_tabs_sides_border_color', '#f4f4f4');

        // Input
        SiteSetting::set('Admin.Colors', 'content_input_font_color', '#555');
        SiteSetting::set('Admin.Colors', 'content_input_background_color', '#fff');
        SiteSetting::set('Admin.Colors', 'content_input_border_color', '#d2d6de');
        SiteSetting::set('Admin.Colors', 'content_input_addon_font_color', '#555');
        SiteSetting::set('Admin.Colors', 'content_input_addon_background_color', '#fff');
        SiteSetting::set('Admin.Colors', 'content_input_addon_border_color', '#d2d6de');

        SiteSetting::set('Admin.Layout', 'theme', 'default');

        $custom_css_markup = file_get_contents(public_path().'/themes/Admin_Theme/css/custom_markup/custom_css_markup.css');
        $admin_colors = SiteSetting::getSection('Admin.Colors');

        foreach ($admin_colors as $key => $value) {
            $custom_css_markup = str_replace('{'.$key.'}', $value, $custom_css_markup);
        }

        file_put_contents(public_path().'/themes/Admin_Theme/css/custom-css.css', $custom_css_markup);
    }
}
