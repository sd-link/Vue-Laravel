<?php

namespace App\Http\Controllers\Backend\Core\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Core\Blog\Post;
use App\Models\Core\Blog\Tag;
use App\Models\Core\Blog\Category;

use DB;
use Content;
use Session;
use Auth;
use Timezone;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('blog', ['except' => ['index', 'create', 'edit']]);
    }

    public function index(Request $request)
    {
        $search = $request->search;
        $filter = $request->filter;
        $status = $request->status;
        $page = $request->page;
        $append = array();

        $content = Post::with('author')->with('categories')->with('tags')->latest();

        if($status){
            switch ($request->status) {
                case 'published':
                    $content = $content->published();
                break;

                case 'drafts':
                    $content = $content->drafts();
                break;

                case 'scheduled':
                    $content = $content->scheduled();
                break;

            }
            $append += array('status' => $status);
        }

        if($search) {
            switch ($filter) {
                case 'username':
                    $content->whereHas('author', function($query) use($search) {
                        $query->where('username', 'LIKE', '%'. $search . '%');
                    });
                break;

                case 'title':
                    $content->where('title', 'LIKE', '%'. $search . '%');
                break;

                case 'category':
                    $content->whereHas('categories', function($query) use($search) {
                        $query->where('title', 'LIKE', '%'. $search . '%');
                    });
                break;

                case 'tag':
                    $content->whereHas('tags', function($query) use($search) {
                        $query->where('title', 'LIKE', '%'. $search . '%');
                    });
                break;

                default:
                    # code...
                break;
            }
            $append += array('search' => $search);
            $append += array('filter' => $filter);
        }

        $content = $content->paginate(3);
        $content->appends($append);

        if($content->lastPage() && !$content->count()) {
            return redirect($content->previousPageUrl());
        }

        $allCount = Post::count();
        $publishedCount = Post::where('status', Content::PUBLISH)->count();
        $draftCount = Post::where('status', Content::DRAFT)->count();
        $scheduledCount = Post::where('status', Content::SCHEDULE)->count();

        $returnedCount = $content->total();

        $tags = Tag::all();
        // $categories = Category::all();
        $categories = Category::with('children')->parents()->orderBy('id', 'asc')->get();

        $content->allCount = $allCount;
        $content->returnedCount = $returnedCount;
        $content->publishedCount = $publishedCount;
        $content->draftCount = $draftCount;
        $content->scheduledCount = $scheduledCount;

        if($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'content' => view('core.blog.posts.includes.content', compact('content'))->render(),
                'counts' => view('components.content.counts', compact('content'))->render(),
                'pagination' => view('components.content.pagination', compact('content'))->render(),
                'page' => $page,
            ], 201);
        }

        return view('core.blog.posts.index', compact('content', 'page', 'search', 'filter', 'categories', 'tags'));
    }

    /**
     * Show Create Post Form.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $tags = Tag::all();
        // $categories = Category::all();

        return view('core.blog.posts.create');
    }

    /**
     * Show Edit Post Form.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        DB::enableQueryLog();

        $post = Post::with('author')->with('categories')->with('tags')->with('featuredimage')
            ->where('id', $id)->first();

        $tags = Tag::all();
        $categories = Category::all();

        return view('core.blog.posts.edit', compact('post', 'tags', 'categories'));
    }

    /**
     * Updates the Post
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $post = $this->save($request);

        $tags = Tag::all();
        $categories = Category::all();
        // load categories and tags
        $post->categories;
        $post->tags;

        if($post) {
            return response()->json([
                'status' => 'success',
                'content' => $post,
                'categories' => $categories,
                'tags' => $tags
            ], 201);
        }

        // return redirect()->route('backend.blog.edit', $post->id);
    }


    /**
     * Saves the Post by either creating a new one or updating existing one
     *
     * @return \Models\Backend\Blog\Post
     */
    protected function save($request)
    {
        $this->validate($request, [
            'title' => 'required|max:160',
        ]);

        $post = Post::firstOrNew(['id' => $request->id]);

        if($request->id == null && Session::has('featured_image'))
            $post->featured_image_id = Session::pull('featured_image');

        $post->title = $request->title;
        $post->body = $request->content;
        $post->status = $request->status;
        $post->access = $request->access;
        $post->published_at = $request->published_at;
        $post->slug = $request->slug;
        $post->password = $request->password;

        if($post->user_id == null)
            $post->user_id = Auth::user()->id;

        $post->save();

        $post->tags = $request->tags;
        $post->categories = $request->categories;

        return $post;
    }

    /**
     * Delete the post.
     *
     * @return json response
     */
    public function delete(Request $request)
    {
        $post = Post::find($request->id);

        // Delete the post
        if($post->delete()) {
            return response()->json([
                'status' => 'success'
            ], 201);
        } else{
            return response()->json([
                'status' => 'error',
                'message' => 'Could not delete this post. Does it still exists?'
            ], 403);
        }
    }
}
