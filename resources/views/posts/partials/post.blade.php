<h3>
    @if($post->trashed())
        <del>
    @endif
    <a class="{{ $post->trashed() ? 'text-muted' : '' }}" href="{{ route('posts.show', ['post' => $post['id']]) }}">{{ $post['title'] }}</a>
    @if($post->trashed())
        </del>
    @endif
</h3>

<p>
    Added {{ $post->created_at->diffForHumans() }}
    by {{ $post->user->name }}
</p>


<p>
    @foreach($post->tags as $tag)
        <a href="{{ route('posts.tags.index', ['tag' => $tag->id]) }}" class="badge alert-success">{{ $tag->name }}</a>
    @endforeach
</p>

@if($post->comments_count)
    <p>{{ $post->comments_count }} comments</p>
@else
    <p>No comments yet!!</p>
@endif


    <div class="mb-3">
        @auth
            @can('update', $post)
                <a href="{{ route('posts.edit', ['post' => $post['id']]) }}" class="btn btn-primary">Edit</a>
            @endcan
        @endauth

        @auth
            @can('delete', $post)
                <form class="d-inline-block" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div><input type="submit" value="Delete!" class="btn btn-primary"></div>
                </form>
            @endcan
        @endauth
    </div>
