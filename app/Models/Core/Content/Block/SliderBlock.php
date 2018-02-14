<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class SliderBlock extends Model
{

    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Slider';
        $settings['position'] = 'absolute';
        $settings['contentRenderStyle'] = 'boxed';
        $settings['width'] = '100%';

        $settings['heightResponsive'] = false;
        $settings['height'] = '450px';
        $settings['heightTabletPortrait'] = '450px';
        $settings['heightTabletLandscape'] = '450px';
        $settings['heightMobilePortrait'] = '450px';
        $settings['heightMobileLandscape'] = '450px';

        $settings['arrows'] =  '1';
        $settings['showArrowsWhen'] =  'allways';
        $settings['buttons'] =  '1';
        $settings['showButtonsWhen'] = 'allways';

        $settings['autoplay'] = '0';
        $settings['autoplayDuration'] = '6000';

        $settings['loop'] = '0';
        $settings['transition'] = 'slideHorisontal';

        $settings['showProgressBar'] = false;
        $settings['progressBarColor'] = '#007AFF';

        return $settings;
    }


    public static function prepareSlides($allBlocks, $subBlocksIds, $block = null) {
        $blocks = [];
        foreach ($subBlocksIds as $key => $blockId) {
            $block = $allBlocks[$blockId];

            if(isset($block->subItems)) {
                $block->subBlocks = self::prepareSlides($allBlocks, $block->subItems);
            }
            $blocks[$key] = $block->toArray();
        }
        return $blocks;
    }
}
