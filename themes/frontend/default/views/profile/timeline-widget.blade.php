<?php $posts = app('App\\Repositories\\PostRepository')->lists('profile', 0, $userId)?>

<div data-type="profile" data-lastcheck="" data-offset="{{\Config::get('post-per-page')}}" data-userid="{{$userId}}" id="post-list">

    @foreach($posts as $post)
        @if(Cache::has('post-'.$post->id))
        {{Cache::get('post-'.$post->id)}}
        @else
        <?php
        $postContent = (String) Theme::section('post.media', ['post' => $post]);
        if (Config::get('enable-query-cache', 0)) Cache::add('post-'.$post->id, $postContent, Config::get('post-cache-time-out', 3));
        ?>
        {{$postContent}}
        @endif
    @endforeach
</div>


@if(count($posts))
<a class="post-load-more" href="">{{trans('post.load-more')}}</a>
@endif

