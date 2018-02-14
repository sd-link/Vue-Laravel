<?php

namespace App\Models\Core\Blog;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Comment extends Model
{

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
