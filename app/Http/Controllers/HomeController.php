<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        return view('home');
    }

    public function home(){
        //dd(Auth::id());
        //dd(Auth::user());
        //dd(Auth::check());
        return view('home.index');
    
    }

    public function contact(){

        return view('home.contact');
    }

    public function secret(){

        return view('home.secret');
    }
}
