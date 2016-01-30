@foreach($comments as $comment)
    @if($comment->post->type == 'page' and $comment->user->id == $comment->post->page->user->id)
        {{Theme::section('comment.display-page', ['comment' => $comment, 'page' => $comment->post->page])}}
    @else
        {{Theme::section('comment.display', ['comment' => $comment])}}
    @endif
@endforeach