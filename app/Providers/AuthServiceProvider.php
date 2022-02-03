<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\BlogPost' => 'App\Policies\BlogPostPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('home.secret', function($user){
            return $user->is_admin;
        });

        //IN PRIMA FAZA ACTIUNEA DE MODIFICAREA SE NUMEA update-post, IN A DOUA FAZA DENUMIREA A FOST posts.update, IAR VARIANTA FINALA ESTE update

        //Autorizare folosind Gate
        /*Gate::define('update-post', function($user, $post){
            return $user->id == $post->user_id;
        });

        Gate::allows('update-post', $post);
        $this->authorize('update-post', $post);

        Gate::define('delete-post', function($user, $post){
            return $user->id == $post->user_id;
        });*/

        //Autorizare folosind Policies
        /*Gate::define('posts.update', 'App\Policies\BlogPostPolicy@update');
        Gate::define('posts.delete', 'App\Policies\BlogPostPolicy@delete');*/

        //Autorizare folosind Policies - metoda scurta, se poate folosi doar daca metodele sunt denumite astfel: posts.create, posts.view, posts.update, posts.delete
        //Gate::resource('posts', 'App\Policies\BlogPostPolicy');

        //DREPTURI ADMIN
        Gate::before(function ($user, $ability){
            if($user->is_admin && in_array($ability, ['update', 'delete'])){
                return true;
            }
        });

        //Gate::after(function ($user, $ability, $result){
        //   if ($user->is_admin){
        //        return true;
        //    }
        //});
        


    }
}
