<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class TextBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] ='Text';
        $settings['textClass'] = '';
        $settings['editor'] = 'basic';
        $settings['textAlign'] = 'left';

        return $settings;
    }
}
