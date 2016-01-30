<?php $posts = app('App\\Repositories\\PostRepository')->lists('user-timeline')?>

<div data-lastcheck="" data-offset="{{\Config::get('post-per-page')}}" data-type="user-timeline"  id="post-list">

    {{Theme::extend('timeline-post')}}
    <?php $counter = 0;?>
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
        {{Theme::extend('timeline-post-'.$counter)}}
        <?php  $counter++?>
    @endforeach

</div>



@if(count($posts))
<a class="post-load-more" href="">{{trans('post.load-more')}}</a>
@else
{{Theme::section('user.home.welcome')}}
@endif