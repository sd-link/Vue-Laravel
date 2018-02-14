<?php

namespace App\Models\Core\Base;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Sluggable;

class Tag extends LaraModel
{
    use Sluggable;

    protected $table = 'tags';
    protected $stiClassField = 'class_name';
    protected $stiBaseClass = 'App\Models\Core\Base\Tag';
}
