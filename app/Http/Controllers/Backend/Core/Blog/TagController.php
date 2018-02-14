<?php

namespace App\Http\Controllers\Backend\Core\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Blog\Tag;

class TagController extends Controller
{

    /**
     * Show tags.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderBy('id', 'asc')->paginate(25);
        return view('core.blog.posts.tags', compact('tags', $tags));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:20'
        ]);

        if($request->ajax()){
            $tag = new Tag();
            $tag->title = $request->title;
            $tag->slug = $tag->slugify($request->title);

            if($request->description)
                $tag->description = $request->description;

            if($tag->save()){
                return response()->json([
                    'status' => 'success',
                    'tag' => $tag,
                    'url' => route('backend.blog.tags.get', $tag->id)
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

        if($request->ajax()){
            $tag = Tag::find($request->id);
            $tag->title = $request->title;
            
            if(!$request->slug)
                $tag->slug = $tag->slugify($request->title);
            else
                $tag->slug = $tag->slugify($request->slug);

            $tag->description = $request->description;

            if($tag->save()){
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


    public function get(Request $request, $id)
    {
        if($request->ajax()){
            $tag = Tag::find($id);
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

    public function delete(Request $request)
    {
        if($request->ajax()){
            $tag = Tag::findOrFail($request->id);
            if($tag->delete()){
                return response()->json([
                    'status' => 'success'
                ], 201);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not delete the tag.'
                ], 401);
            }
        }
    }

}
