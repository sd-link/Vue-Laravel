<?php

namespace App\Models\Core\Settings;

use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

class SiteSetting extends Model
{
    protected $table = "site_settings";

    protected $fillable = ['key', 'value', 'type', 'section'];

    static public function set($section, $key, $value, $type='string')
    {
        // $setting = SiteSetting::where('section', $section)->where('key', $key)->first();

        $setting = self::updateOrCreate(
            ['key' => $key, 'section' => $section],
            ['key' => $key, 'value' => $value, 'type' => $type, 'section' => $section]
        );

        // if($setting) {
        //     $setting->section = $section;
        //     $setting->key = $key;
        //     $setting->value = $value;
        //     $setting->update();
        // } else {
        //     $setting = new SiteSetting();
        //     $setting->section = $section;
        //     $setting->key = $key;
        //     $setting->value = $value;
        //     $setting->save();
        // }
    }

    static public function getAdminLayout()
    {
        $layout = SiteSetting::where('section', 'Admin.Layout')->where('key', 'layout')->first();

        return $layout->value;
    }

    static public function getDeviceType()
    {
        $agent = new Agent();

        switch (true) {
            case $agent->isDesktop():
                return 'desktop';
            break;

            case $agent->isTablet():
                return "tablet";
            break;

            case $agent->isMobile():
                return "mobile";
            break;
        }
    }

    static public function isDesktop()
    {
        $agent = new Agent();
        return $agent->isDesktop();
    }

    static public function isTablet()
    {
        $agent = new Agent();
        return $agent->isTablet();
    }

    static public function isMobile()
    {
        $agent = new Agent();
        if($agent->isTablet())
            return false;
        else
            return $agent->isMobile();
    }

    static public function getAdminLayoutCssClass() {
        $layout = SiteSetting::where('section', 'Admin.Layout')->where('key', 'layout')->first();

        switch ($layout->value) {
            case 'topnav-wide':
                return 'layout-top-nav';
            break;

            case 'sidebar':
                return 'sidebar-mini';
            break;
        }
    }

    static public function getColumnWidth()
    {
        $layout = SiteSetting::where('section', 'Admin.Layout')->where('key', 'layout')->first();

        switch ($layout->value) {
            case 'topnav-wide':
                return 12;
            break;

            case 'sidebar':
                return 6;
            break;
        }
    }

    static public function getValue($section, $key, $prepareForVue = false)
    {
        $setting = SiteSetting::where('section', $section)->where('key', $key)->first();

        if($prepareForVue) {
            if($setting->type == 'boolean') {
                $value = filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
                $value = ($value) ? 'true' : 'false';
                $setting = 'init' . ucfirst($setting->key) . ': ' . $value . ', ';
            } else if($setting->type == 'string') {
                $setting = 'init' . ucfirst($setting->key) . ': ' . '\'' . $setting->value . '\'' . ', ';
            } else if($setting->type == 'integer') {
                $setting = 'init' . ucfirst($setting->key) . ': ' . $setting->value . ', ';
            }
        }

        switch ($setting->value) {
            case 'true':
                $setting->value = filter_var('true', FILTER_VALIDATE_BOOLEAN);
            break;
            case 'false':
                $setting->value= filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
            break;
        }

        return $setting->value;
    }

    static public function getSection($section, $prepareForVue = false)
    {
        $settings = SiteSetting::where('section', $section)->select('key', 'value', 'type')->get();

        if($prepareForVue)
            return self::settingsPreparedForVue($settings);

        foreach ($settings as $key => $value) {
            switch ($value) {
                case 'true':
                    $settings[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                break;
                case 'false':
                    $settings[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                break;
            }
        }
        // $settings = (object) $settings;

        return $settings;
    }

    static public function get($section, $key)
    {
        $setting = SiteSetting::where('section', $section)->where('key', $key)->select('key', 'value', 'type')->first();


            switch ($setting['value']) {
                case 'true':
                    $setting['value'] = filter_var($setting['value'], FILTER_VALIDATE_BOOLEAN);
                break;
                case 'false':
                    $setting['value'] = filter_var($setting['value'], FILTER_VALIDATE_BOOLEAN);
                break;
            }

        // $settings = (object) $settings;

        return $setting['value'];
    }

    static protected function settingsPreparedForVue($siteSettings)
    {
        $settings = '{';
        foreach ($siteSettings as $key => $setting) {
            if($setting->type == 'boolean') {
                $value = filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
                $value = ($value) ? 'true' : 'false';
                $settings .= 'init' . ucfirst($setting->key) . ': ' . $value . ', ';
            } else if($setting->type == 'string') {
                $settings .= 'init' . ucfirst($setting->key) . ': ' . '\'' . $setting->value . '\'' . ', ';
            } else if($setting->type == 'integer') {
                $settings .= 'init' . ucfirst($setting->key) . ': ' . $setting->value . ', ';
            }
        }
        $settings .= '}';
        return $settings;
    }
}
