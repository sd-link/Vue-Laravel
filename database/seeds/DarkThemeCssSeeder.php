<?php

use Illuminate\Database\Seeder;

class DarkThemeCssSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SiteSetting::set('Admin.Layout', 'theme', 'dark');

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
        SiteSetting::set('Admin.Colors', 'content_container_background_color', 'rgba(255,255,255,0.015)');

        SiteSetting::set('Admin.Colors', 'content_published_background_color', 'rgba(0, 0, 0, 0.09)');
        SiteSetting::set('Admin.Colors', 'content_schedule_background_color', 'rgba(0, 0, 0, 0.25)');
        SiteSetting::set('Admin.Colors', 'content_draft_background_color', 'rgba(0, 0, 0, 0.25)');
        SiteSetting::set('Admin.Colors', 'content_dates_background_color', 'rgba(0, 0, 0, 0.09)');

        // Content Table
        SiteSetting::set('Admin.Colors', 'content_table_thead_background_color', 'rgba(0,0,0, 0.12)');
        SiteSetting::set('Admin.Colors', 'content_table_tbody_tr_even_background_color', 'rgba(0,0,0, 0.085)');
        SiteSetting::set('Admin.Colors', 'content_table_tbody_tr_odd_background_color', 'rgba(0,0,0, 0.085)');
        SiteSetting::set('Admin.Colors', 'content_table_tbody_tr_hover_background_color', 'rgba(0,0,0, 0.15)');

        SiteSetting::set('Admin.Colors', 'content_row_header_background_color', 'rgba(0,0,0, 0.12)');
        SiteSetting::set('Admin.Colors', 'content_row_even_background_color', 'rgba(0,0,0, 0.085)');
        SiteSetting::set('Admin.Colors', 'content_row_odd_background_color', 'rgba(0,0,0, 0.04)');
        SiteSetting::set('Admin.Colors', 'content_row_hover_background_color', 'rgba(0,0,0, 0.15)');

        // Box
        SiteSetting::set('Admin.Colors', 'content_box_background_color', '#222D32');
        SiteSetting::set('Admin.Colors', 'content_box_header_footer_border_color', 'rgba(41,56,62,0.82)');

        // Tabs
        SiteSetting::set('Admin.Colors', 'content_tabs_menu_background_color', 'rgba(50,70,77,0.0)');
        SiteSetting::set('Admin.Colors', 'content_tabs_menu_bottom_border_color', '#446792');
        SiteSetting::set('Admin.Colors', 'content_tabs_background_color', '#243137');
        SiteSetting::set('Admin.Colors', 'content_tabs_top_border_color', 'rgba(0,0,0,0)');
        SiteSetting::set('Admin.Colors', 'content_tabs_sides_border_color', '#446792');

        // Input
        SiteSetting::set('Admin.Colors', 'content_input_font_color', 'rgba(159,194,213,0.7)');
        SiteSetting::set('Admin.Colors', 'content_input_background_color', '#283339');
        SiteSetting::set('Admin.Colors', 'content_input_border_color', 'rgba(17,17,17,0.39)');
        SiteSetting::set('Admin.Colors', 'content_input_addon_font_color', '#b8c7ce');
        SiteSetting::set('Admin.Colors', 'content_input_addon_background_color', '#1e292e');
        SiteSetting::set('Admin.Colors', 'content_input_addon_border_color', 'rgba(17,17,17,0.39)');

        // CodeMirror 243137
        SiteSetting::set('Admin.Colors', 'markdown_editor_toolbar_background', '#243137');
        SiteSetting::set('Admin.Colors', 'markdown_editor_toolbar_icon_color', '#5d8ab8');
        SiteSetting::set('Admin.Colors', 'markdown_editor_toolbar_icon_background', 'rgba(252, 252, 252, 0.01)');
        SiteSetting::set('Admin.Colors', 'markdown_editor_toolbar_icon_border_color', 'rgba(149, 165, 166, 0.13)');

        $custom_css_markup = file_get_contents(public_path().'/themes/Admin_Theme/css/custom_markup/custom_css_markup.css');
        $admin_colors = SiteSetting::getSection('Admin.Colors');

        // dd($admin_colors);

        foreach ($admin_colors as $key => $setting) {
            $custom_css_markup = str_replace('{'.$setting['key'].'}', $setting['value'], $custom_css_markup);
        }

        file_put_contents(public_path().'/themes/Admin_Theme/css/custom-css.css', $custom_css_markup);
    }
}
