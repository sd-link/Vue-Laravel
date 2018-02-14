<?php

namespace App\Models\Core\Blog;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Base\Content;
use App\Models\User;
use App\Models\Traits\Sluggable;
use App\Models\Traits\Commentable;

class Post extends Content
{
    use Sluggable;
    use Commentable;

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'content_category', 'content_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'content_tag', 'content_id', 'tag_id');
    }

    public function getTagIdsAttribute()
    {
        return $this->tags->pluck('id');
    }

    public function setTagsAttribute($tags)
    {
        $tags = Tag::processNew($tags);
        $this->tags()->sync($tags);
    }

    public function setCategoriesAttribute($categories)
    {
        $categories = Category::processNew($categories);
        $this->categories()->sync($categories, true);
    }

}
