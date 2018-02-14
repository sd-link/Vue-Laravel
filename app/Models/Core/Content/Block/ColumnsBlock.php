<?php

namespace App\Models\Core\Content\Block;

use Illuminate\Database\Eloquent\Model;

class ColumnsBlock extends Model
{
    private $settings = array();
    public function getDefaults()
    {
        $settings['blockTitle'] = 'Columns';
        $settings['columnsClass'] = '';
        $settings['contentRenderStyle'] = 'fluid';

        $settings['visibleDesktop'] =  '1';
        $settings['visibleTablet'] =  '1';
        $settings['visibleMobile'] =  '1';

        $settings['minHeight'] = '180px';

        $settings['paddingResponsive'] = '0';
        $settings['padding'] = '0px';
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
