<?php

namespace App\Http\Controllers\Backend\Core\Media;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Media\GalleryTag;

class GalleryTagController extends Controller
{
    public function index()
    {
        $tags = GalleryTag::orderBy('id', 'asc')->paginate(25);
        return view('core.media.tags.gallery', compact('tags', $tags));
    }

    public function all(Request $request)
    {
        // $tags = GalleryTag::select('id', 'title', 'slug', 'class_name')->get();
        //
        // if($tags->count()) {
        //     return response()->json([
        //         'status' => 'success',
        //         'tags' => $tags
        //     ], 201);
        // } else {
        //     return response()->json([
        //         'status' => 'success',
        //         'tags' => null
        //     ], 201);
        // }
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:20'
        ]);

        if($request->ajax()) {
            $tag = new GalleryTag();
            $tag->title = $request->title;
            $tag->slug = $tag->slugify($request->title);

            if($request->description)
                $tag->description = $request->description;

            if($tag->save()){
                return response()->json([
                    'status' => 'success',
                    'tag' => $tag,
                    'url' => route('backend.media.gallery.tags.get', $tag->id)
                ], 201);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not create the tag.'
                ], 401);
            }
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:20'
        ]);

        if($request->ajax()) {
            $tag = GalleryTag::find($request->id);
            $tag->title = $request->title;

            if(!$request->slug)
                $tag->slug = $tag->slugify($request->title);
            else
                $tag->slug = $tag->slugify($request->slug);

            $tag->description = $request->description;

            if($tag->save()) {
                return response()->json([
                    'status' => 'success',
                    'tag' => $tag
                ], 201);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not update the tag.'
                ], 401);
            }
        }
    }

    public function delete(Request $request)
    {
        if($request->ajax()) {
            $tag = GalleryTag::findOrFail($request->id);
            if($tag->delete()) {
                return response()->json([
                    'status' => 'success'
                ], 201);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not delete the tag.'
                ], 401);
            }
        }
    }

    public function get(Request $request, $id)
    {
        if($request->ajax()){
            $tag = GalleryTag::find($id);
            if($tag){
                return response()->json([
                    'status' => 'success',
                    'id' => $tag->id,
                    'title' => $tag->title,
                    'slug' => $tag->slug,
                    'description' => $tag->description,
                ], 201);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not find this tag. Perhaps it was deleted.'
                ], 404);
            }
        }
    }
}
