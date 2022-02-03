@extends('layouts.app')

@section('title', $post->title)

@section('content')
<div class="row">
    <div class="col-8">
        <h1>
            {{ $post->title }}
            @if(now()->diffInMinutes($post->created_at) < 5)
                <div class="badge alert-success">New!</div>
            @endif
        </h1>

        <p>{{ $post->content }}</p>
        
        <p>Added {{ $post->created_at->diffForHumans() }}</p>

        <p>
            @foreach($post->tags as $tag)
                <a href="{{ route('posts.tags.index', ['tag' => $tag->id]) }}" class="badge alert-success">{{ $tag->name }}</a>
            @endforeach
        </p>

        <p>Currently read by {{ $counter }} people</p>

        <p>Files</p>
        <p>
            <img src="{{ URL('storage/thumbnails/686.png') }}" height="30px" width="30px">
        </p>

        <h4>Comments</h4>

        @include('comments.form')

        @forelse( $post->comments as $comment )
            <p> {{ $comment->content }} </p>
            <p class="text-muted"> {{ $comment->created_at->diffForHumans() }} </p>
        @empty
            <p>No comments yet!!!</p>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts.partials.activity')
    </div>
</div>
@endsection