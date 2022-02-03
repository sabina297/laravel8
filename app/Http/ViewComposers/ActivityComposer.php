<?php

namespace App\Http\ViewComposers;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Catch_;

class ActivityComposer
{
    public function compose(View $view)
    {
        $mostCommented = Cache::remember('mostCommented', now()->addSeconds(10), function () {
            return BlogPost::mostCommented()->take(5)->get();
        }); 

        $mostActiv = Cache::remember('users-most-activ', now()->addSeconds(10), function () {
            return User::withMostBlogPosts()->take(5)->get();
        }); 

        $mostActivLastMonth = Cache::remember('users-most-activ-last-month', now()->addSeconds(10), function () {
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        }); 

        $view->with('mostCommented', $mostCommented);
        $view->with('mostActiv', $mostActiv);
        $view->with('mostActivLastMonth', $mostActivLastMonth);
    }
}