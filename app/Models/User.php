<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Core\Blog\Post;

use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use LaratrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first', 'last', 'slug', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function gravatar()
    {
        $email = "nermion@gmail.com";
        $default = "http://i.imgur.com/oPYEzsj.png";
        $size = 100;

        return "https://www.gravatar.com/avatar/" . md5( strtolower( trim( $email ) ) ) . "?d=" . urlencode( $default ) . "&s=" . $size;
    }

    public function delete()
    {
        $this->posts->each->delete(); 
        return parent::delete();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
}
