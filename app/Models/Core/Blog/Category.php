<?php

namespace App\Models\Core\Blog;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Base\Category as BaseCategory;

class Category extends BaseCategory
{
    protected $fillable = ['title', 'slug'];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'content_category');
    }

    public function scopeParents($query)
    {
        return $query->where('parent', '=', null);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent', 'id')->orderBy('id', 'asc');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Add any new categories
    static public function processNew($categories)
    {
        $data = array();

        if($categories) {
            foreach($categories as $key => $value){
                if(!is_numeric($value)){
                    $tag = new Category();
                    $tag->title = $value;
                    $tag->slug = $tag->slugify($value);
                    $tag->save();
                    $data += [$key => $tag->id];
                }
            }

            $temp = array();
            $temp = $categories;

            foreach ($data as $key => $value) {
                unset($temp[$key]);
            }

            $categories = array_merge($temp,$data);
        }
        return $categories;
    }
}
