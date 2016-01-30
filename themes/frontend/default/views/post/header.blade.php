@if ($post->type == 'user-timeline')
    {{Theme::section('post.headers.user', ['post' => $post])}}
@elseif ($post->type == "community")
    {{Theme::section('post.headers.community', ['post' => $post])}}
@elseif($post->type == 'page')
    {{Theme::section('post.headers.page', ['post' => $post])}}
@endif