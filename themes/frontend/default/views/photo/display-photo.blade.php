<div class='photo album'>
    <a class='preview-image post-thumb-image' rel='album' href='{{Image::url($photo->path, '960')}}'><img src='{{Image::url($photo->path, 200)}}'/></a>
    <div class="photo-time">
        <span class="post-time">
        <span title="{{$photo->time()}}">{{$photo->created_at}}</span>
    </span>
    </div>
</div>