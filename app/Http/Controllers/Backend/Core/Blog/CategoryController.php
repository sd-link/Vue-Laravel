<?php

namespace App\Http\Controllers\Backend\Core\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Blog\Category;
use File;

class CategoryController extends Controller
{
    /**
     * Show categories.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::with('children')->parents()->orderBy('id', 'asc')->paginate(25);
        $content = view('core.blog.posts.categories', compact('categories', $categories))->render();

        return view('core.blog.posts.categories', compact('categories', $categories));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:40'
        ]);

        if($request->ajax()){
            $category = new Category();
            $category->title = $request->title;
            $category->slug = $category->slugify($request->title);

            if($request->description)
                $category->description = $request->description;

            if($request->parent != 0)
                $category->parent = $request->parent;

            if($category->save()){
                return response()->json([
                    'status' => 'success',
                    'id' => $category->id,
                    'url' => route('backend.blog.categories.get', $category->id)
                ], 201);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not create the category.'
                ], 401);
            }
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:40'
        ]);

        if($request->ajax()){
            $category = Category::findOrFail($request->id);
            $category->title = $request->title;
            // $category->slug = null;
            $category->description = $request->description;

            if($request->parent != 0)
                $category->parent = $request->parent;
            else
                $category->parent = null;

            // get previous user id
            $previous = Category::where('id', '<', $category->id)->max('id');
            $next = Category::where('id', '>', $category->id)->min('id');

            if($category->save()){
                return response()->json([
                    'status' => 'success',
                    'id' => $category->id,
                    'previous' => $previous,
                    'next' => $next,
                ], 201);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not update the category.'
                ], 401);
            }
        }
    }


    public function get(Request $request, $id)
    {
        if($request->ajax()){
            $category = Category::find($id);
            if($category){
                return response()->json([
                    'status' => 'success',
                    'id' => $category->id,
                    'title' => $category->title,
                    'parent' => $category->parent,
                    'description' => $category->description,
                ], 201);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not find this category. Perhaps it was deleted.'
                ], 401);
            }
        }
    }

    public function getParentCategories(Request $request)
    {
        if($request->ajax()) {

            $categories = Category::parents()->orderBy('id', 'asc')->select('id', 'title')->get();

            if($categories) {
                return response()->json([
                    'status' => 'success',
                    'categories' => $categories,
                        ], 201);
                    } else{
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Could not find any categories.'
                        ], 401);
                    }
        }
    }

    public function delete(Request $request)
    {
        if($request->ajax()){
            if(Category::where('id', $request->id)->orWhere('parent', $request->id)->delete()){
                return response()->json([
                    'status' => 'success'
                ], 201);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Could not delete the category.'
                ], 401);
            }
        }
    }
}
