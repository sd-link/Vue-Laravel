<?php
namespace App\Models\Core\Settings;

use Auth;

trait HasSettings {

    public function settings()
    {
        return $this->morphToMany(Setting::class, 'settingable');
    }

    public function getSettingsFilteredForVue()
    {
        $settings = '{';
        foreach ($this->settings as $key => $setting) {
            if($setting['type'] == 'boolean') {
                $value = filter_var($setting['value'], FILTER_VALIDATE_BOOLEAN);
                $value = ($value) ? 'true' : 'false';
                $settings .= 'init' . ucfirst($setting['key']) . ': ' . $value . ', ';
            } else if($setting['type'] == 'string') {
                $settings .= 'init' . ucfirst($setting['key']) . ': ' . '\'' . $setting['value'] . '\'' . ', ';
            } else if($setting->type == 'integer') {
                $settings .= 'init' . ucfirst($setting->key) . ': ' . $setting['value'] . ', ';
            }
        }
        $settings .= '}';
        return $settings;
    }

    public function getSettings() {
        $settings = array();
        if(isset($this->type)) {
            $class = $this->type;
            $block = new $class;
            if(method_exists($block, 'getDefaults')) {
                $settings = $block->getDefaults();
            }
        }

        foreach ($this->settings as $key => $setting) {
            $settings[$setting['key']] = $setting['value'];
        }
        // if($this->type == 'ContainerBlock')
        //     dump($settings);
        return (object)$settings;
    }

    public function getSettingsNoDefaults() {
        $settings = array();
        foreach ($this->settings as $key => $setting) {
            $settings[$setting['key']] = $setting['value'];
        }
        return (object)$settings;
    }

    public function getSetting($key)
    {
        return $this->settings()->where('key', $key)->first();
    }

    public function exists($key)
    {
        return $this->settings()->where('key', $key)->exists();
    }

    public function setSetting($key, $value, $type = 'string')
    {
        $setting = $this->settings()->updateOrCreate(
            ['key' => $key],
            ['key' => $key, 'value' => $value, 'type' => $type]
        );

        return true;
    }

    public function removeSetting($key)
    {
        $toDelete = $this->getSetting($key);
        $toDelete->delete();
        $this->settings()->detach($toDelete->id);
    }
}
