<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class ImageBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {

        $settings['blockTitle'] ='Image';
        $settings['imageTitleEnabled'] = false;
        $settings['imageTitleClass'] ='h2';
        $settings['imageTitlePosition'] ='1';
        $settings['showLabel'] = true;

        $settings['imageCaptionEnabled'] = false;
        $settings['imageCaptionClass'] ='caption';

        $settings['imageSize'] ='original';
        $settings['imageClass'] ='img-responsive img-center';

        return $settings;
    }
}
