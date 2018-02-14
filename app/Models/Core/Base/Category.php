<?php

namespace App\Models\Core\Base;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Sluggable;

class Category extends LaraModel
{
    use Sluggable;

    protected $table = 'categories';
    protected $stiClassField = 'class_name';
    protected $stiBaseClass = 'App\Models\Core\Base\Category';
}
