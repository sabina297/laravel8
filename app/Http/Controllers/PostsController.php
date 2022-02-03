<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Models\User;;
use Illuminate\Support\Facades\Cache;
use Illuminate\Routing\RedirectController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Catch_;

//use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth'); -- toate metodele din clasa pot fi accesate doar de catre utilizatorii conectati
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']); // -- sunt protejate dosar metodele specificate in array
    }

    private $posts = [
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

    /**s
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //comments_count        

        return view(
            'posts.index', 
            [ 
                    'posts' => BlogPost::LatestWithRelations()->get(),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {  
        $validated = $request->validated(); 
        $validated['user_id'] = $request->user()->id    ;
        $posts = BlogPost::create($validated);
        
        $hasFile = $request->hasFile('thumbnail'); //se verifica daca fisierul a fost incarcat
        dump($hasFile);

        if($hasFile)
        {
            $file = $request->file('thumbnail');
            dump($file);
            dump($file->getClientMimeType()); //tipul fisierului
            dump($file->getClientOriginalExtension()); // tipul original al fisierului

            //dump($file->store('thumbnails')); // Stocarea fisierului

            $name1 = $file->storeAs('thumbnails', $posts->id . '.' . $file->getClientOriginalExtension()); //Stocarea fisierului cu dumirea predefinita
            $name2 = Storage::disk('public')->putFileAs('thumbnails', $file, $posts->id . '.' . $file->getClientOriginalExtension()); //echivalent cu secventa de mai sus + salvearea cu specificare disk local
            
            dump(Storage::url($name1)); // afiseaza url-ul fisierului
            dump(Storage::disk('public')->url($name2)); // afisaza o cale relativa a fisierului, pt a obtine url-ul trebuie setat in filesystems.php pentru 'local' proprietatea url
        }
        die;

        $request->session()->flash('status', 'The new blog posts was created');

        return redirect()->route('posts.show', ['post' => $posts->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //abort_if(!isset($this->posts[$id]), 404);

        //ORDONAREA COMENTARIILOR FOLOSIND LOCAL SCOPES I
        //return view('posts.show', ['post' => BlogPost::with(['comments' => function($query){
        //    return $query->latest();
        //}])->findOrfail($id)]);

        
        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60, function() use($id) {
            return BlogPost::with('comments')->with('tags')->with('user')->findOrFail($id);
        });
        
         //CATI VIZITATORI curenti ARE SITE-UL
         $sessionId = session()->getId();
         $counterKey = "blog-post-{$id}-counter";
         $usersKey = "blog-post-{$id}-users";
 
         $users = Cache::get($usersKey, []);
         $usersUpdate = [];
         $diffrence = 0;
         $now = now();
 
         foreach ($users as $session => $lastVisit) {
             if ($now->diffInMinutes($lastVisit) >= 1) {
                 $diffrence--;
             } else {
                 $usersUpdate[$session] = $lastVisit;
             }
         }
 
         if(
             !array_key_exists($sessionId, $users)
             || $now->diffInMinutes($users[$sessionId]) >= 1
         ) {
             $diffrence++;
         }
 
         $usersUpdate[$sessionId] = $now;
         Cache::forever($usersKey, $usersUpdate);
 
         if (!Cache::has($counterKey)) {
             Cache::forever($counterKey, 1);
         } else {
             Cache::increment($counterKey, $diffrence);
         }
         
         $counter = Cache::get($counterKey);

        return view('posts.show', [
            'post' => $blogPost,
            'counter' => $counter
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $post = BlogPost::findOrfail($id);

        /*if(Gate::denies('update-post', $post)){
            abort(403, "You can't edit this blog post");
        }*/

        $this->authorize($post); //permite utilizatorului sa editeze doar postarile introduse de catre el -> autorizarea prin policies

        return view('posts.edit', ['post' => BlogPost::findOrfail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrfail($id);

        /*if(Gate::denies('update-post', $post)){
            abort(403, "You can't edit this blog post");
        }*/   /// echivalent cu 

        $this->authorize($post); // permite utilizatorului sa editeze doar postarile introduse de catre el

        $validated = $request->validated();
        
        $post->fill($validated)->save();

        $request->session()->flash('status', 'The blog was updated');

        return redirect()->route('posts.show', ['post' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrfail($id);

        /*if(Gate::denies('delete-post', $post)){
            abort(403, "You can't delete this blog post");
        }*/

        $this->authorize($post); //permite utilizatorului sa stearga doar postarile introduse de catre el

        $post->delete();

        session()->flash('status', 'The blog was deleted!');

       return redirect()->route('posts.index');
    }
}
