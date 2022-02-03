@extends('layouts.app')

@section('title','Contract')

@section('content')
    <div><h1>Contract page!!</h1></div>


    @can('home.secret')
        <p>
            <a href="{{ route('secret') }}"> Go to special contact details!</a>
        </p>
    @endcan

@endsection