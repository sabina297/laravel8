<p>
    @foreach($tags as $tag)
        <a href="#" class="badge alert-success">{{ $tag->name }}</a>
    @endforeach
</p>