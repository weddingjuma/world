{{Theme::extend('community-timeline-before-editor')}}

@if($community->present()->canPost())
    {{Theme::section('post.editor.main', [
        'offPrivacy' => true,
        'type' => 'community',
        'community_id' => $community->id,
        'privacy' => (($community->privacy == 1) ? 1 : 6)
    ])}}
@endif

{{Theme::extend('community-timeline-after-editor')}}
<div data-type="community-{{$community->id}}" data-lastcheck="" data-offset="{{\Config::get('post-per-page')}}"  id="post-list">

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




