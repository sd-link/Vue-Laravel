<?php

use Illuminate\Database\Seeder;

class SiteSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('site_settings')->truncate();

        SiteSetting::set('Comments', 'enable', true, 'boolean');
        SiteSetting::set('Comments', 'type', 'native');
        SiteSetting::set('Comments', 'must_be_registered', false, 'boolean');
        SiteSetting::set('Comments', 'allow_nested', true, 'boolean');
        SiteSetting::set('Comments', 'nested_level', '2');
        SiteSetting::set('Comments', 'order', 'oldest');
        SiteSetting::set('Comments', 'must_approve', true, 'boolean');
        SiteSetting::set('Comments', 'allow_approved', true, 'boolean');
        SiteSetting::set('Comments', 'email_admin_on_new_comments', true, 'boolean');
        SiteSetting::set('Comments', 'email_admin_on_moderation', true, 'boolean');
        SiteSetting::set('Comments', 'hide_comments_from_menu', false, 'boolean');

        SiteSetting::set('Admin.Login', 'login_background_color', '#4c8daa');
        SiteSetting::set('Admin.Login', 'login_background_image', '');
        SiteSetting::set('Admin.Login', 'login_logo_text', '');
        SiteSetting::set('Admin.Login', 'login_logo_text_color', '');
        SiteSetting::set('Admin.Login', 'login_logo_image', '');
        SiteSetting::set('Admin.Login', 'login_horisontal_position', 'center');
    }
}
