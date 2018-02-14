<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;


use App\Models\Core\Settings\HasSettings;

class ContainerBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Container';
        $settings['containerType'] = 'boxed';

        $settings['visibleDesktop'] =  '1';
        $settings['visibleTablet'] =  '1';
        $settings['visibleMobile'] =  '1';

        $settings['minHeight'] = '180px';
        $settings['height'] = 'auto';

        $settings['display'] = 'block';
        $settings['flexDirection'] = 'column';
        $settings['justifyContent'] = 'center';
        $settings['alignItems'] = 'center';

        $settings['paddingResponsive'] = '0';
        $settings['padding'] = '0px 15px';
        $settings['paddingTabletPortrait'] = '0px 15px';
        $settings['paddingTabletLandscape'] = '0px 15px';
        $settings['paddingMobilePortrait'] = '0px 15px';
        $settings['paddingMobileLandscape'] = '0px 15px';

        $settings['marginResponsive'] = '0';
        $settings['margin'] = '0px auto';
        $settings['marginTabletPortrait'] = '0px auto';
        $settings['marginTabletLandscape'] = '0px auto';
        $settings['marginMobilePortrait'] = '0px auto';
        $settings['marginMobileLandscape'] = '0px auto';

        $settings['background'] = null;
        $settings['backgroundImage'] = null;
        $settings['backgroundStyle'] = 'scroll';
        $settings['backgroundSize'] = 'cover';
        $settings['backgroundSizeManual'] = '100%';
        $settings['backgroundPosition'] = 'center';
        $settings['backgroundRepeat'] = 'repeat';
        $settings['backgroundColor'] = 'transparent';
        return $settings;
    }
}
