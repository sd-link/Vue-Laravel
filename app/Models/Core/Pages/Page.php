<?php

namespace App\Models\Core\Pages;

use Illuminate\Database\Eloquent\Model;

use App\Models\Core\Base\Content;
use App\Models\User;
use App\Models\Traits\Sluggable;
use App\Models\Traits\TranslatableModel;

class Page extends Content
{
    use Sluggable;
    use TranslatableModel;
}
