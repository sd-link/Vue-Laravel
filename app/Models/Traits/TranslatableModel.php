<?php
namespace App\Models\Traits;

use App\Models\Core\Base\ModelTranslator;
use Auth;

trait TranslatableModel {

    public function translations()
    {
        return $this->morphMany(ModelTranslator::class, 'translatable');
    }

    public function translated($key, $locale)
    {
        return $this->translations()->where('key', $key)->where('locale', $locale)->exists();
    }

    public function getTranslation($key, $locale)
    {
        return $this->translations()->where('key', $key)->where('locale', $locale)->first();
    }

    public function translate($key, $locale, $value = null)
    {
        if(!$value)
            return $this->translations()->where('key', $key)->first()->value('value');
        else {
            if($this->translated($key, $locale)) {
                $translate = $this->getTranslation($key, $locale);
                $translate->value = $value;
                $translate->save();
                return true;
            } else {
                $translate = new ModelTranslator();
                $translate->user_id = Auth::user()->id;
                $translate->key = $key;
                $translate->locale = $locale;
                $translate->value = $value;
                $this->translations()->save($translate);
                return true;
            }
        }
    }
}
