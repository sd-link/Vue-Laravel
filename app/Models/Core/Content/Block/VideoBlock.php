<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class VideoBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] ='Video';
        $settings['videoTitleEnabled'] = false;
        $settings['videoTitleClass'] ='h2';
        $settings['position'] ='center';

        $settings['imageCaptionEnabled'] = false;
        $settings['videoCaptionClass'] ='caption';

        $settings['width'] ='100%';
        $settings['height'] ='480';

        return $settings;
    }
}
