<?php

namespace App\Models\Core\Comments;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $table = 'comments_subscribers';

    // content status
    const SUBSCRIBE_ALL = 1;
    const SUBSCRIBE_NEW_COMMENTS = 2;
    const SUBSCRIBE_MY_COMMENTS = 3;
    const UNSUBSCRIBE = 4;
}
