<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class ImagesBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Images';
        $settings['blockClass'] = '';

        $settings['imagesTitleEnabled'] = false;
        $settings['imagesCaptionEnabled'] = false;
        $settings['imagesTitleClass'] = 'h2';
        $settings['imagesCaptionClass'] = '';

        $settings['imageCaptionEnabled'] = false;
        $settings['imageCaptionClass'] = 'caption';

        $settings['spaceBetweenImages'] = '5px';
        $settings['imageClass'] = 'img-responsive img-center';

        $settings['imageWidth'] = '5px';
        $settings['imageBackgroundColor'] = 'transparent';
        $settings['imagePadding'] = '0px';

        $settings['imageBorder'] ='0px';
        $settings['imageBorderColor'] = 'transparent';

        $settings['imageOutline'] = '0px';
        $settings['imageOutlineColor'] = 'transparent';

        $settings['imageSize'] = 'grid_large';
        $settings['columnsPerRow'] = 3;
        $settings['imageAmount'] = 3;
        $settings['canAddImages'] = 3;

        return $settings;
    }
}
