<?php

namespace App\Models\Core\Blog;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Base\Tag as BaseTag;

class Tag extends BaseTag
{
    protected $fillable = ['title', 'slug'];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'content_tag');
    }

    // Add any new tags
    static public function processNew($tags)
    {
        $data = array();
        if($tags) {
            foreach($tags as $key => $value) {
                if(!is_numeric($value)) {
                    $tag = new Tag();
                    $tag->title = $value;
                    $tag->slug = $tag->slugify($value);
                    $tag->save();
                    $data += [$key => $tag->id];
                }
            }

            $temp = array();
            $temp = $tags;

            foreach ($data as $key => $value) {
                unset($temp[$key]);
            }

            $tags = array_merge($temp,$data);
        }
        return $tags;
    }
}
