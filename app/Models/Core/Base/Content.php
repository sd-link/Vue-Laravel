<?php

namespace App\Models\Core\Base;

use App\Models\Core\Base\LaraModel;
use App\Models\User;
use App\Models\Core\Media\Image;
use Auth;
use Timezone;
use Carbon\Carbon;

class Content extends LaraModel
{
    protected $table = 'content';
    protected $stiClassField = 'class_name';
    protected $stiBaseClass = 'App\Models\Core\Base\Content';
    protected $fillable = ['title', 'body', 'user_id', 'status', 'slug', 'access'];

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

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contentblocks()
    {
        return $this->hasMany(ContentBlock::class, 'content_id');
    }

    public function featuredimage()
    {
        return $this->belongsTo(Image::class, 'featured_image_id');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // $request = request();
            // dd($request->all());
        });

        static::saved(function ($model) {
            // dd("saved content".$model->id);
        });
   }

    // public function setUserIdAttribute()
    // {
    //
    // }

    public function setStatusAttribute($status)
    {
        if($status)
            $this['status'] = $status;
    }

    public function setAccessAttribute($access)
    {
        if($access)
            $this['status'] = $access;
        else {
            $this['status'] = self::EVERYONE;
        }
    }

    public function getPasswordAttribute()
    {
        if(isset($this->meta['password']))
            return $this->meta['password'];
        else
            return null;
    }

    public function setPasswordAttribute($password)
    {
        $meta = [];
        $meta = $this->meta;
        if($password == '')
            $meta['password'] = null;
        else
            $meta['password'] = $password;
        $this->meta = $meta;
    }

    public function scopeLatestz($query)
    {
        $query->orderBy('created_at', 'desc');
    }

    public function scopeLatestPublished($query)
    {
        $query->orderBy('published_at', 'desc')->where('status', self::PUBLISH);
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

    public function scopePopular($query)
    {
        $query->orderBy('views', 'desc');
    }

    public function getUpdatedAtAttribute()
    {
        if(Auth::guest()) {
            $updated_at = new Carbon($this->attributes['updated_at'], 'UTC');
            return $updated_at->format('Y-m-d\TH:i\Z');
        }
        else {
            $timezone = Auth::user()->timezone;
            return Timezone::convertFromUTC($this->attributes['updated_at'], $timezone, 'Y-m-d H:i');
        }
    }

    public function getCreatedAtAttribute()
    {
        if(Auth::guest()) {
            $created_at = new Carbon($this->attributes['created_at'], 'UTC');
            return $created_at->format('Y-m-d\TH:i\Z');
        }
        else {
            $timezone = Auth::user()->timezone;
            return Timezone::convertFromUTC($this->attributes['created_at'], $timezone, 'Y-m-d H:i');
        }
    }

    public function getPublishedAtAttribute()
    {
        if(Auth::guest())
            return $this->attributes['published_at']->format('Y-m-d\TH:i\Z');
        else {
            $timezone = Auth::user()->timezone;
            if(isset($this->attributes['published_at']))
                return Timezone::convertFromUTC($this->attributes['published_at'], $timezone, 'Y-m-d H:i');
            else
                return null;
        }
    }

    public function setPublishedAtAttribute($published_at)
    {
        if($published_at) {
            $published_at_temp = new Carbon($published_at, Auth::user()->timezone);
            $this->attributes['published_at'] = Timezone::convertToUTC($published_at_temp->addSeconds(59), Auth::user()->timezone);
        }
        elseif(!$this->published_at) {
            $this->attributes['published_at'] = Carbon::now();
        }
    }

}
