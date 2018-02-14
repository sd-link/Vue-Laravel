<?php

namespace App\Models\Core\Media;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Traits\Sluggable;
use App\Models\Core\Settings\HasSettings;
use Session;

class Gallery extends Model
{
    use Sluggable;
    use HasSettings;

    protected $fillable = ['id', 'description', 'title', 'slug'];

    // sorting order
    const CUSTOM = 1;
    const RANDOM = 2;
    const UPLOAD_DATE = 3;

    // Title & Caption choices
    const YES = 1;
    const NO = 2;

    // Description choices
    const DESCRIPTION_NO_DISPLAY = 1;
    const DESCRIPTION_BELOW = 2;
    const DESCRIPTION_ABOVE = 3;

    public function images()
    {
        return $this->belongsToMany(Image::class, 'gallery_image', 'gallery_id', 'image_id')->withPivot('order');
    }

    public function tags()
    {
        return $this->belongsToMany(GalleryTag::class, 'gallery_tag', 'gallery_id', 'tag_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setTagsAttribute($tags)
    {
        $tags = GalleryTag::processNewTags($tags);
        $this->tags()->sync($tags, true);
    }

    public function pickImagesFromSession()
    {
        if(Session::has('images')) {
            $order = 1;
            foreach (Session::pull('images') as $key => $image_id) {
               $this->images()->attach([$image_id => ['order' => $order]]);
               $order = $order + 1;
            }

            $this->save();
        }
    }


}
