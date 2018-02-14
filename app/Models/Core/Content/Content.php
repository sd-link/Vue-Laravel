<?php

namespace App\Models\Core\Content;

use Illuminate\Database\Eloquent\Model;
// use App\Models\Core\Base\Content;
use App\Models\User;
use App\Models\Traits\Sluggable;
use App\Models\Traits\Commentable;
use App\Models\Core\Taxonomies\Term;
use App\Models\Core\Media\Image;
use Auth;
use Timezone;
use Carbon\Carbon;

use App\Models\Core\Settings\HasSettings;

class Content extends Model
{
    use Sluggable;
    use HasSettings;

    protected $table = "content";

    // content status
    const PUBLISH = 1;
    const DRAFT = 2;
    const SCHEDULE = 3;

    // content access
    const EVERYONE = 1; // accessable by public
    const MEMBERS = 2; // anyone that is a member on the site
    const PREMIUM_MEMBERS = 3; // accessable only be premium members, can be different types of premium members

    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
        'published_at'
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function type()
    {
        return $this->belongsTo(ContentType::class, 'content_type_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function featuredimage()
    {
        return $this->belongsTo(Image::class, 'featured_image_id');
    }

    // public function type()
    // {
    //     return $this->belongsTo(ContentType::class, 'content_type_id', 'id');
    // }

    public function terms()
    {
        return $this->belongsToMany(Term::class, 'content_term', 'content_id', 'term_id');
    }

    public function blocks()
    {
        return $this->hasMany(ContentBlock::class, 'content_id', 'id');
    }

    public function scopePublished($query)
    {
        $query->where('status', self::PUBLISH);
    }

    public function scopeDrafts($query)
    {
        $query->where('status', self::DRAFT);
    }

    public function scopeScheduled($query)
    {
        $query->where('status', self::SCHEDULE);
    }


    // public function getUpdatedAtAttribute()
    // {
    //     if(Auth::guest()) {
    //         $updated_at = new Carbon($this->attributes['updated_at'], 'UTC');
    //         return $updated_at->format('Y-m-d\TH:i\Z');
    //     }
    //     else {
    //         $timezone = Auth::user()->timezone;
    //         return Timezone::convertFromUTC($this->attributes['updated_at'], $timezone, 'Y-m-d H:i');
    //     }
    // }

    // public function getCreatedAtAttribute()
    // {
    //     if(Auth::guest()) {
    //         $created_at = new Carbon($this->attributes['created_at'], 'UTC');
    //         return $created_at->format('Y-m-d\TH:i\Z');
    //     }
    //     else {
    //         $timezone = Auth::user()->timezone;
    //         return Timezone::convertFromUTC($this->attributes['created_at'], $timezone, 'Y-m-d H:i');
    //     }
    // }

    // public function getPublishedAtAttribute()
    // {
    //     return $this->attributes['published_at'];
    // }

    public function setPublishedAtAttribute($publishAt)
    {
        if($publishAt) {

                $this->attributes['published_at'] = new Carbon($publishAt); //Timezone::convertToUTC($published_at_temp->addSeconds(59), Auth::user()->timezone);
        }
        else {
            if(!isset($this->published_at))
                $this->attributes['published_at'] = Carbon::now();
        }
    }
}
