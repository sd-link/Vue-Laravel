<?php

namespace App\Models\Core\Base;

use Auth;
use Timezone;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Sluggable;

class Image extends LaraModel
{
    use Sluggable;
    protected $table = 'images';
    protected $stiClassField = 'class_name';
    protected $stiBaseClass = 'App\Models\Core\Base\Image';
    protected $fillable = ['title', 'user_id', 'slug'];


    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
        'published_at'
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function getOriginalAttribute()
    {
        return asset($this->path . $this->filename . '.' . $this->extension);
    }

    public function getThumbAttribute()
    {
        return asset($this->path . $this->filename . '_thumb.' . $this->extension);
    }

    public function getThumbLargeAttribute()
    {
        return asset($this->path . $this->filename . '_thumb_large.' . $this->extension);
    }

    public function getMediumAttribute()
    {
        return asset($this->path . $this->filename . '_medium.' . $this->extension);
    }

    public function getLargeAttribute()
    {
        return asset($this->path . $this->filename . '_large.' . $this->extension);
    }

    public function getGridMediumAttribute()
    {
        return asset($this->path . $this->filename . '_grid_medium.' . $this->extension);
    }

    public function getGridLargeAttribute()
    {
        return asset($this->path . $this->filename . '_grid_large.' . $this->extension);
    }

    public function scopeLatest($query)
    {
        $query->orderBy('created_at', 'desc');
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
}
