<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class ColumnBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Columns';
        $settings['columnClass'] = '';
        $settings['width'] = '50%';

        $settings['visibleDesktop'] =  '1';
        $settings['visibleTablet'] =  '1';
        $settings['visibleMobile'] =  '1';

        $settings['display'] = 'block';
        $settings['flexDirection'] = 'column';
        $settings['justifyContent'] = 'center';
        $settings['alignItems'] = 'center';

        $settings['minHeight'] = '180px';

        $settings['heightResponsive'] = '0';
        $settings['height'] = 'auto';
        $settings['heightTablet'] = '50px';
        $settings['heightMobile'] = '50px';

        $settings['paddingResponsive'] = '0';
        $settings['padding'] = '10px';
        $settings['paddingTablet'] = '0px';
        $settings['paddingMobile'] = '0px';

        $settings['marginResponsive'] = '0';
        $settings['margin'] = '0px auto';
        $settings['marginTablet'] = '0px auto';
        $settings['marginMobile'] = '0px auto';

        $settings['columnSpacing'] = '2px';
        $settings['selectedColumns'] = 'two-equal';

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
