<?php

namespace App\Http\Controllers\Backend\Core\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Settings\SiteSetting;
use App\Models\Core\Settings\AdminMenu;
use Artisan;
use Admin;

class AdminMenuController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getSection('Admin.Colors');
        // dd($settings);
        return view('core.settings.admin_menu.index', compact('settings'));
    }

    public function contentSave(Request $request)
    {
        if($request->ajax()) {
            foreach ($request->except('_token') as $key => $value) {
                SiteSetting::set('Admin.Content', $key, $value);
            }

            Admin::configure();

            return response()->json([
                'status' => 'success',
            ], 201);
        }
    }

    public function layoutThemeSave(Request $request)
    {
        if($request->ajax()) {

            $layout = $request->layout;
            $theme = $request->theme;

            switch ($layout) {
                case 'sidebar':
                    Artisan::call('db:seed', [
                            '--class' => 'SidebarLayoutSeeder'
                        ]);
                break;

                case 'topnav-wide':
                    Artisan::call('db:seed', [
                            '--class' => 'TopNavWideLayoutSeeder'
                        ]);
                break;

                case 'topnav-boxed':
                    Artisan::call('db:seed', [
                            '--class' => 'TopNavBoxedLayoutSeeder'
                        ]);
                break;
            }

            switch ($theme) {
                case 'default':
                    Artisan::call('db:seed', [
                            '--class' => 'DefaultThemeCssSeeder'
                        ]);
                    SiteSetting::set('Admin.Layout', 'theme', $theme);
                break;

                case 'dark':
                    Artisan::call('db:seed', [
                            '--class' => 'DarkThemeCssSeeder'
                        ]);
                    SiteSetting::set('Admin.Layout', 'theme', $theme);
                break;

                case 'bright':
                    Artisan::call('db:seed', [
                            '--class' => 'BrightThemeCssSeeder'
                        ]);
                    SiteSetting::set('Admin.Layout', 'theme', $theme);
                break;
            }


            return response()->json([
                'status' => 'success',
            ], 201);
        }
    }

    public function itemSave(Request $request)
    {
        $menu_item = AdminMenu::where('id' , $request->id)->first();
        $menu_item->update($request->all());
        if($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'menu_item' => $menu_item,
                'id' => $request->id,
                'visible' => $request->visible
            ], 201);
        }
    }

    public function customcssSave(Request $request)
    {
        if($request->ajax()) {
            $custom_css_markup = file_get_contents('themes/Admin_Theme/css/custom_markup/custom_css_markup.css');
            foreach ($request->except('_token') as $key => $value) {
                SiteSetting::set('Admin.Colors', $key, $value);
                $custom_css_markup = str_replace('{'.$key.'}', $value, $custom_css_markup);
            }

            file_put_contents('themes/Admin_Theme/css/custom-css.css', $custom_css_markup);

            return response()->json([
                'status' => 'success',
            ], 201);
        }
    }

    // public function contentSiteSettingsSave(Request $request)
    // {
    //     if($request->ajax()) {
    //         $custom_content_markup = file_get_contents('themes/Admin_Theme/css/custom_markup/custom_content_markup.css');
    //         foreach ($request->except('_token') as $key => $value) {
    //             SiteSetting::set('Admin.MainMenu', $key, $value);
    //             $custom_content_markup = str_replace('{'.$key.'}', $value, $custom_content_markup);
    //         }
    //
    //         file_put_contents('themes/Admin_Theme/css/custom-content.css', $custom_content_markup);
    //
    //         return response()->json([
    //             'status' => 'success',
    //         ], 201);
    //     }
    // }

    public function itemOrder(Request $request)
    {
        $order = 10;

        $admin_menu = AdminMenu::all();

        foreach ($request->ids as $key => $id) {
            $menu_item = AdminMenu::where('id' , $id)->first();
            $menu_item->order = $order;
            $menu_item->save();
            $order = $order + 10;
        }

        return response()->json([
            'status' => 'success',
        ], 201);
    }

    public function loginSave(Request $request)
    {
        if($request->ajax()) {
            // $custom_login_markup = file_get_contents('themes/Admin_Theme/css/custom_markup/custom_login_markup.css');
            SiteSetting::set('Admin.Login', 'custom_login', 1);
            foreach ($request->except('_token') as $key => $value) {
                SiteSetting::set('Admin.Login', $key, $value);
                // $custom_login_markup = str_replace('{'.$key.'}', $value, $custom_login_markup);
            }

            // file_put_contents('themes/Admin_Theme/css/custom-login.css', $custom_login_markup);

            return response()->json([
                'status' => 'success',
            ], 201);
        }
    }
}
