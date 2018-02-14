<?php

namespace App\Models\Core\Base;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Sluggable;

class Taxonomy extends LaraModel
{
    use Sluggable;

    protected $table = 'taxonomies';
    protected $stiClassField = 'class_name';
    protected $stiBaseClass = 'App\Models\Core\Base\Taxonomy';
}
