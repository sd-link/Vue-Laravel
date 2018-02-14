<?php
namespace App\Models\Traits;

use App\Models\Core\Taxonomies\Taxonomy;
use App\Models\Core\Taxonomies\Term;
use Auth;
use Purifier;
use Setting;

trait HasTerms {

    public function terms()
    {
        return $this->morphMany(Term::class, 'termable')->where('status', 1);
    }

    public function getTaxonomy($id)
    {
        return $this->taxonomies()->where('id', $id)->first();
    }

    public function addTerm($title)
    {
        echo "title: " . $this->title;
        $taxonomy = $this->terms()->create([$title]);
        return $taxonomy;
    }

    public function addTerm($title, $taxonomy, $parent = null)
    {
        $term = new Term();
        $term->title = $title;
        $term->parent = $parent->id;
        $term->taxonomy_id = $taxonomy->id;
        $term->save();
    }

    public function deleteTerm($title, $taxonomy)
    {
        $term = $this->getTerm($title, $taxonomy);
        $term->delete();
    }

    public function getTerm($term, $taxonomy)
    {
		$taxonomy = $this->taxonomies->where('title', $taxonomy)->first();

		return Term::where('taxonomy_id', $taxonomy->id)->where('name', '=', $term)->first();
    }

    public function getTerms($taxonomy)
    {
        $taxonomy = $this->taxonomies->where('title', $taxonomy)->first();

        return Term::whereIn('taxonomy_id', $taxonomy->id)->get();
    }
}
