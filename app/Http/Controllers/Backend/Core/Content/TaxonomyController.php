<?php

namespace App\Http\Controllers\Backend\Core\Content;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Content\ContentType;
use App\Models\Core\Taxonomies\Taxonomy;
use App\Models\Core\Taxonomies\Term;

class TaxonomyController extends Controller
{
    public function taxonomy($type, $slug)
    {
        $contentType = ContentType::where('slug', $type)->first();
        $tax = Taxonomy::with('terms')->where('content_type_id', $contentType->id)->where('slug', $slug)->first();
        return view('core.content.taxonomy.index', compact('tax'));
    }

    public function getTerms($type, $slug)
    {
        $contentType = ContentType::where('slug', $type)->first();
        $tax = Taxonomy::with('terms')->where('content_type_id', $contentType->id)->where('slug', $slug)->first();

        return response()->json($tax);
    }

    public function saveTerm(Request $request)
    {
        $termData = (object)$request->term;
        $term = Term::updateOrCreate(
            ['id' => $termData->id],
            ['name' => $termData->name, 'description' => $termData->description, 'taxonomy_id' => $termData->taxonomy_id]
        );

        $term->slug = $termData->name;
        $term->save();
        return response()->json([
            'status' => 'success',
        ], 201);
    }

    public function deleteTerm(Request $request)
    {
        $termData = (object)$request->term;
        $term = Term::where('id', $termData->id)->first();

        $term->delete();

        return response()->json([
            'status' => 'success',
        ], 201);
    }
}
