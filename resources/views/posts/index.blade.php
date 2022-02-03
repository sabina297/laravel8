@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')
<div class="row">
    <div class="col-8">
        @forelse($posts as $key => $post)
            @include('posts.partials.post', [])
        @empty
            <div>No post!!!!</div>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts.partials.activity')
    </div>
</div>
@endsection 