<?php

namespace App\Models\Core\Media;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Core\Base\Image as BaseImage;
use App\Models\Traits\Sluggable;

class Image extends BaseImage
{
    use Sluggable;

    protected $appends = ['original', 'thumb', 'thumb_large', 'medium', 'large', 'grid_medium', 'grid_large'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function galleries()
    {
        return $this->belongsToMany(Gallery::class, 'gallery_image');
    }

    static public function imageUrl($imageSize, $image) {
        switch ($imageSize) {
            case 'original':
                return '/' . $image->path .  $image->filename . '.' .  $image->extension;
            break;

            case 'large':
                return '/' .  $image->path .  $image->filename . '_large.' .  $image->extension;
            break;

            case 'medium':
                return '/' .  $image->path .  $image->filename . '_medium.' .  $image->extension;
            break;

            case 'grid_large':
                return '/' .  $image->path .  $image->filename . '_grid_large.' .  $image->extension;
            break;

            case 'grid_medium':
                return '/' .  $image->path .  $image->filename . '_grid_medium.' .  $image->extension;
            break;

            case 'thumb_large':
                return '/' .  $image->path .  $image->filename . '_thumb_large.' .  $image->extension;
            break;

            case 'thumb':
                return '/' .  $image->path .  $image->filename . '_thumb.' .  $image->extension;
            break;

            default:
                return '/' .  $image->path .  $image->filename . '_grid_medium.' .  $image->extension;
        }
    }


}
