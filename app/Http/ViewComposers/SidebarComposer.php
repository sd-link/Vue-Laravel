<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Models\Core\Blog\Category;
use App\Models\Core\Blog\Post;

class SidebarComposer
{

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $this->composeCategories($view);
        $this->composePopularPosts($view);
    }

    private function composeCategories(View $view)
    {
        $categories = Category::with(array('posts' => function ($query) { $query->published(); }))->orderBy('title', 'asc')->get();
        $view->with('categories', $categories);
    }

    private function composePopularPosts(View $view)
    {
        $popularPosts = POST::published()->popular()->limit(3)->get();
        $view->with('popularPosts', $popularPosts);
    }

}