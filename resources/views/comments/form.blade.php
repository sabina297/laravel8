<div class="mb-2 mt-2">
@auth
    <form action="{{ route('posts.comments.store', ['post' => $post->id]) }}" method="POST">
        @csrf

        <div class="form-group">    
            <textarea type="text" name="content" class="form-control"></textarea>
        </div>

        <input type="submit" value="Add comment" class="btn btn-primary btn-block mt-2">
    </form>
    @error('content')
        <div class="alert alert-danger mt-2"> {{ $message }} </div>
    @enderror
@else
    <a href="{{ route('login') }}">Sing-in</a> to post comments!
@endauth
</div>