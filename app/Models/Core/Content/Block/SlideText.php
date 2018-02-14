<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class SlideText extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Layer';
        $settings['contentClass'] = 'white';
        return $settings;
    }
}
