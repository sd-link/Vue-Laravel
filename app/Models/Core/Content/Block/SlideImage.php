<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class SlideImage extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Layer';
        $settings['contentClass'] = '';
        $settings['imageResponsive'] = true;
        $settings['imagePosition'] = 'center';
        $settings['content'] = '';

        return $settings;
    }
}
