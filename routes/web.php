<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('home.index');
//})->name('home.index');

//Route::get('/contact', function(){
//    return view('home.contact');
//})->name('home.contact');

Route::get('/', [HomeController::class, 'home'])->name('home.index')
//s->middleware('Auth')    ->  Protejarea rutei - poate fi accesata doar de catre utilizatorii autentificati
;
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::get('/single', AboutController::class);
Route::get('/secret', [HomeController::class, 'secret'])->name('secret')->middleware('can:home.secret, ');  //home.secret este numele abilitatii din AuthServiceProvider

Route::resource('posts',  PostsController::class);//->only('index', 'show', 'create', 'store', 'edit', 'update');
Route::get('/posts/tag/{tag}', 'App\Http\Controllers\PostTagController@index')->name('posts.tags.index');

Route::resource('posts.comments', 'App\Http\Controllers\PostCommentController')->only(['store']);

$posts = [
    1 => [
        'title' => 'Intro to Laravel',
        'content' => 'This is a short intro to Laravel',
        'is_new' => true
    ],
    2 => [
        'title' => 'Intro to PHP',
        'content' => 'This is a short intro to PHP',
        'is_new' => false
    ],
    3 => [                  
        'title' => 'Intro to Gooooo',
        'content' => 'This is a short intro to Gooooo',
        'is_new' => false
    ]
];

/*Route::get('/posts', function() use($posts){
    return view('posts.index', [ 'posts' => $posts ]);
});

Route::get('/posts/{id}', function ($id) use($posts) {
    
    abort_if(!isset($posts[$id]), 404);

    return visw('posts.show', ['post' => $posts[$id]]);
})->name('posts.show');*/

Route::get('/recent-posts/{days_ago?}', function ($daysAgo = 20) {
    return 'Post from ' . $daysAgo . ' days ago';
})->name('post.recent.index')->middleware('auth');

Route::prefix('/fun')->name('fun.')->group(function() use($posts){
    Route::get('responses', function() use($posts){
    
        return response($posts, 201)
            ->header('Content-Type', 'application/json')
            ->cookie('MY_Cookie', 'Sabina', 3600);
    
    })->name('resposes');
    
    Route::get('redirect', function(){
        return redirect('/contact');
    })->name('redirect');
    
    Route::get('back', function(){
        return redirect()->back();
    })->name('back');
    
    Route::get('route-name', function(){
        return redirect()->route('posts.show', ['id' => 1]);
    })->name('route-name');
    
    Route::get('away', function(){
        return redirect()->away('https://google.com');
    })->name('away');
    
    Route::get('json', function() use($posts){
        return response()->json($posts);
    })->name('json');
    
    Route::get('download', function(){
        return response()->download(public_path('imagine.jpeg'), 'face.jpeg');
    })->name('download');
}); 
Route::get('/logout', 'App\Http\Controllers\Auth\LoginController@logout');
Auth::routes();

