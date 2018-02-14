<?php

namespace App\Models\Core\Comments;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Comment extends Model
{
    protected $fillable = ['name', 'email', 'website', 'review', 'status', 'meta', 'title', 'body', 'user_id', 'parent', 'visitor_ip'];

    protected $dates = [
        'created_at',
        'deleted_at',
        'updated_at',
    ];

    const APPROVED = 1;
    const PENDING = 2;
    const SPAM = 3;

    public function commentable()
    {
        return $this->morphTo();
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeApproved($query)
    {
        $query->where('status', self::APPROVED);
    }

    public function scopePending($query)
    {
        $query->where('status', self::PENDING);
    }

    public function scopeSpam($query)
    {
        $query->where('status', self::SPAM);
    }

    public function getStatusAttribute()
    {
        $this->attributes['status'] = (int)$this->attributes['status'];
        return $this->attributes['status'];
    }

    public function subscribe($request)
    {
        if($request->subscribe) {
            $subscriber = Subscriber::where('email', $request->email)->where('content_id', $request->content_id)->first();

            if(!$subscriber) {
                $subscriber = new Subscriber();
                $subscriber->content_id = $request->content_id;
                $subscriber->name = $request->name;
                $subscriber->email = $request->email;
            }

            $subscriber->subscribe = $request->subscribe;
            $subscriber->user_id = $request->user_id;

            $subscriber->save();
        }
    }

    public function scopeParents($query)
    {
        return $query->where('parent', '=', null);
    }

    public function scopeLatest($query)
    {
        return $query->where('status', '!=', self::SPAM);
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent', 'id')->where('status', self::APPROVED)->orderBy('id', 'asc');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function subscribers()
    {
        return $this->hasMany(Subscribers::class);
    }
}
