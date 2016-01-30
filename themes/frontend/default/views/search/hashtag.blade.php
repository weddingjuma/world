<div class="box">
    <div class="box-title">{{trans('hashtag.trending-hashtag')}}</div>
    <div class="box-content trending-hashtags" id="">
        @foreach($hashtags as $hashtag)
            <a data-ajaxify="true" href="{{URL::to('search/hashtag?term='.ltrim(str_replace('#', '', $hashtag->hash)))}}">{{$hashtag->hash}}</a>
        @endforeach
    </div>
</div>

@if(!count($posts))
    <div class="box">
        <div class="box-content">
            <div class="alert alert-danger">
                {{trans('search.no-hashtags', ['term' => $searchRepository->term])}}
                </div>
        </div>
    </div>
@endif

<div data-offset="{{\Config::get('post-per-page')}}" data-type="search-#{{$searchRepository->term}}"  id="post-list">
                {{Theme::extend('search-post-list')}}
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