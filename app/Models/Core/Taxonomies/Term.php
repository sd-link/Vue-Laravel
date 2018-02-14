<?php

namespace App\Models\Core\Taxonomies;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Sluggable;

class Term extends Model
{
    use Sluggable;

    protected $fillable = ['name', 'slug', 'description', 'order', 'taxonomy_id'];

    public function parent()
    {
        return $this->belongsTo(Term::class, 'parent');
    }

    public function children()
    {
        return $this->hasMany(Term::class, 'parent', 'id')->orderBy('id', 'asc');
    }
}
