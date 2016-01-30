<?php $details = $post->present()->getAutoPost();?>

@if($details['type'] == 'change-avatar')
    <div class=" one-image" >

        <a class="preview-image post-thumb-one-image" rel="{{$post->id}}-images" href="{{Image::url($details['avatar'], '600')}}"><img src="{{Image::url($details['avatar'], 600)}}"/></a>

    </div>
@elseif($details['type'] == 'add-photos')
    <?php $photos = $post->present()->getAutoPostPhotos()?>
    <div class=" {{(count($photos) > 1) ? 'post-images' : 'one-image'}}" >

        @foreach($photos as $photo)

        <a class="preview-image  {{(count($photos) > 1) ? 'post-thumb-image' : 'post-thumb-one-image'}}" rel="{{$post->id}}-images" href="{{Image::url($photo, '600')}}"><img src="{{Image::url($photo, 600)}}"/></a>
        @endforeach

    </div>
@elseif($details['type'] == 'like-page')
    {{Theme::section('page.display', ['page' => $details['page']])}}
@elseif($details['type'] == 'like-game')
{{Theme::section('game.display', ['game' => $details['game']])}}
@endif