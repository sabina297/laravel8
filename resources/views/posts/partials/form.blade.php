<div class="form-group">
    <label for="title">Title</label>
    <input type="text" id="title" name='title' class="form-control" value="{{ old('title', optional($post ?? null)->title?? '') }}">
</div>

@error('title')
    <div class="alert alert-danger mt-2">{{ $message }}</div>
@enderror

<div class="form-group">
    <label for="content">Content</label>
    <input type="text" id="content" name='content' class="form-control" value="{{ old('content', optional($post ?? null)->content ?? '') }}">
</div>

<div class="form-group mt-2">
    <label for="title">Thumbnail</label><br>
    <input type="file" id="thumbnail" name='thumbnail' class="form-control-fiel" >
</div>

@error('content')
    <div class="alert alert-danger mt-2"> {{ $message }} </div>
@enderror