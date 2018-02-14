<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class SlideLayer extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Layer';
        $settings['display'] = 'flex';
        $settings['flexDirection'] = 'column';
        $settings['justifyContent'] = 'center';

        $settings['alignItems'] =  'center';
        $settings['paddingResponsive'] =  'false';
        $settings['padding'] =  '0px';
        $settings['paddingTablet'] = '0px';
        $settings['paddingMobile'] = '0px';
        return $settings;
    }
}
