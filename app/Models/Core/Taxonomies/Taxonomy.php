<?php

namespace App\Models\Core\Taxonomies;

use Illuminate\Database\Eloquent\Model;
use App\Models\Core\Settings\HasSettings;
use App\Models\Traits\Sluggable;

class Taxonomy extends Model
{
    use Sluggable;
    use HasSettings;

    protected $fillable = ['taxonomy_id', 'name', 'slug', 'description', 'order'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function terms()
    {
        return $this->hasMany(Term::class, 'taxonomy_id', 'id');
    }

    static public function createOrUpdate($content_type_id, $taxonomyData)
    {
        $taxonomy = Taxonomy::where('id', $taxonomyData['dbid'])->where('content_type_id', $content_type_id)->first();

        if($taxonomy) {
            $taxonomy->content_type_id = $content_type_id;
            $taxonomy->name = $taxonomyData['taxonomyName'];
            $taxonomy->name_singular = $taxonomyData['taxonomyNameSingular'];
            $taxonomy->order = $taxonomyData['order'];
            $taxonomy->update();
        } else {
            $taxonomy = new Taxonomy();
            $taxonomy->content_type_id = $content_type_id;
            $taxonomy->name = $taxonomyData['taxonomyName'];
            $taxonomy->name_singular = $taxonomyData['taxonomyNameSingular'];
            $taxonomy->order = $taxonomyData['order'];
            $taxonomy->save();
        }

        return $taxonomy;
    }

}
