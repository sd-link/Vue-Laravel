<?php

use Illuminate\Database\Seeder;

class TopNavWideLayoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TopNavigation Wide Layout
        SiteSetting::set('Admin.Layout', 'layout', 'topnav-wide');
        SiteSetting::set('Admin.Colors', 'main_header_navbar_min_heigh', '65', 'integer');

        SiteSetting::set('Admin.Colors', 'main_header_navbar_li_a_padding_top', '23', 'integer');
        SiteSetting::set('Admin.Colors', 'main_header_navbar_li_a_padding_bottom', '23', 'integer');

        SiteSetting::set('Admin.Colors', 'main_navbar_brand_padding_top_bottom', '20', 'integer');
        SiteSetting::set('Admin.Colors', 'main_navbar_brand_padding_sides', '15', 'integer');
    }
}
