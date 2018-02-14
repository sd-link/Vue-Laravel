<?php

use Illuminate\Database\Seeder;

class SidebarLayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TopNavigation Boxed Layout
        SiteSetting::set('Admin.Layout', 'layout', 'sidebar');
        SiteSetting::set('Admin.Colors', 'main_header_navbar_min_heigh', '50', 'integer');

        SiteSetting::set('Admin.Colors', 'main_header_navbar_li_a_padding_top', '15', 'integer');
        SiteSetting::set('Admin.Colors', 'main_header_navbar_li_a_padding_bottom', '15', 'integer');

        SiteSetting::set('Admin.Colors', 'main_navbar_brand_padding_top_bottom', '15', 'integer');
        SiteSetting::set('Admin.Colors', 'main_navbar_brand_padding_sides', '15', 'integer');
    }
}
