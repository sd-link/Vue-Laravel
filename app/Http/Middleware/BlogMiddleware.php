<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Core\Blog\Post;

class BlogMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if(!$user->hasRole(['admin', 'editor', 'author']))
            abort(403);

        // if post exists we are trying to update it
        $post = Post::find($request->id);

        // are we updating existing post or creating a new one
        if($post) {
            if(!$user->owns($post) && $user->hasRole(['author']))
                abort(403);
        }


        return $next($request);
    }
}
