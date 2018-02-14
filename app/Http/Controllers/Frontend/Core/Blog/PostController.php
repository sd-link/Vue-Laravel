<?php

namespace App\Http\Controllers\Frontend\Core\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Core\Blog\Post;
use App\Models\Core\Blog\Category;
// use App\Models\Core\Comments\Comment;
use App\Models\User;
use App\Models\Core\Comments\Comment;
use App\Models\Core\Comments\Subscriber;
use Shortcode;
use Setting;
use Session;

class PostController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth');
        Shortcode::enable();
    }

    public function comment(Request $request) {
        $post = Post::where('id',$request->content_id)->first();

        if($request->ajax()) {
            if($post) {
                // $request['visitor_ip'] = $request->ip();
                $comment = $post->comment($request);
                return response()->json([
                    'status' => true
                ], 201);
            } else {
                return response()->json([
                    'error' => 'Post has been deleted.'
                ], 401);
            }
        } else {
            if($post) {
                $comment = $post->comment($request);
                Session::put('comment-posted', $comment->id);
                Session::put('comment-status', $comment->status);
                return redirect()->route('frontend.blog.show', $post->slug);
            }
        }
    }

    /**
     * Show the posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // \DB::enableQueryLog();
        $posts = Post::with('author')->with('categories')
            ->latestPublished()
            ->simplePaginate(15);

        return view('blog.index', compact('posts'));
        // dd(\DB::getQueryLog());
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->first();
        $parser = new \cebe\markdown\GithubMarkdown();
        $post->body = $parser->parse($post->body);

        $comments = Comment::where('commentable_id', $post->id)->with('childrenRecursive')->parents()->latest()->approved()->orderBy('id', Setting::get('Comments', 'order'))->get();

        foreach ($comments as $key => $comment) {
            $comment->level = 1;
            $this->setLevelOnComment($comment);
        }
        return view('blog.show', compact('post', 'comments'));
    }

    private function setLevelOnComment($comment)
    {
        $level = $comment->level + 1;
        foreach ($comment->childrenRecursive as $key => $comment) {
            $comment->level = $level;
            $this->setLevelOnComment($comment);
        }
    }

    public function category(Category $category)
    {
        $categoryName = $category->title;
        $posts = $category->posts()
            ->with('author')
            ->with('categories')
            ->latestPublished()
            ->simplePaginate(5);

        return view('blog.index', compact('posts', 'categoryName'));
    }


    public function author(User $author)
    {
        $authorName = $author->firstname . ' ' . $author->lastname;
        $posts = $author->posts()
            ->with('categories')
            ->latestPublished()
            ->simplePaginate(5);

        return view('blog.index', compact('posts', 'authorName'));
    }

}
