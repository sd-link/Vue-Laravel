<?php

namespace App\Models\Core\Media;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Sluggable;
use App\Models\Core\Base\Tag as BaseTag;

class ImageTag extends Model
{
    use Sluggable;

    protected $fillable = ['title', 'slug'];
    protected $table = 'image_tags';

    public function images()
    {
        return $this->belongsToMany(Image::class, 'image_tag');
    }

    // Add any new tags
    static public function processNewTags($tags)
    {
        $data = array();

        if($tags) {
            foreach($tags as $key => $value) {
                if(!is_numeric($value)) {
                    $tag = new ImageTag();
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
