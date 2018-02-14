<?php

namespace App\Models\Core\Content;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Base\Content;
use App\Models\User;
use App\Models\Traits\Sluggable;
use App\Models\Traits\Commentable;
use App\Models\Traits\HasTaxonomies;

class Post extends Content
{
    use Sluggable;
    use HasTaxonomies;
    // use Commentable;
}


// <?php
//
// namespace App\Models\Core\Content\Post;
//
// use Illuminate\Database\Eloquent\Model;
// use App\Models\Core\Base\Content;
// use App\Models\User;
// use App\Models\Traits\Sluggable;
// use App\Models\Traits\Commentable;
//
// class Post extends Content
// {
//     use Sluggable;
//     // use HasTaxonomies;
//     // use Commentable;
// }
