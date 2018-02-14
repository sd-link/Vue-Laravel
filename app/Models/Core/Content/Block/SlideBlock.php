<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class SlideBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Slide';
        return $settings;
    }
}
