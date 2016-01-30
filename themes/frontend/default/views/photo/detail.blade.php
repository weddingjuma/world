<div class="user user-mini media ">
    <?php

        $avatar = $photo->user->present()->getAvatar(100);
        $ownerLink = $photo->user->present()->url();
        $canDelete = $photo->isOWner();
        $name = $photo->user->fullname;

        if ($photo->page_id) {
            $page = $photo->page;
            if ($page) {
                $avatar = $page->present()->getAvatar(100);
                $ownerLink = $page->present()->url();
                if ($page->present()->isAdmin() or $page->present()->isEditor()) $canDelete = true;
                $name = $page->title;
            }
        }

    ?>
      <div class="media-object pull-left">
           <a href="{{$ownerLink}}" data-ajaxify="true"><img src="{{$avatar}}"/></a>
      </div>
      <div class="media-body">
          <h5 class="media-heading">{{$name}}</h5>
          @if(Auth::check() and $canDelete)

                <a class="delete-photo" href="{{URL::route('delete-photo', ['id' => $photo->id])}}">{{trans('global.delete')}}</a>

          @endif
      </div>

</div>

<div class="clearfix">
    <ul class="pull-left nav">
        @if($photo->post_id and $photo->post)
        <?php $hasLike = $photo->post->hasLiked()?>

        <a data-is-login="{{Auth::check()}}" data-status="{{($hasLike) ? '1' : 0}}" class="like-button" data-like="{{trans('like.like')}}" data-unlike="{{trans('like.unlike')}}" data-id="{{$photo->post->id}}" data-type="post" href=""><i class="icon ion-ios7-heart"></i> <span>{{($hasLike) ? trans('like.unlike') : trans('like.like')}}</span></a>
        @else
        <?php $hasLike = $photo->hasLiked()?>

        <a data-is-login="{{Auth::check()}}" data-status="{{($hasLike) ? '1' : 0}}" class="like-button" data-like="{{trans('like.like')}}" data-unlike="{{trans('like.unlike')}}" data-id="{{$photo->id}}" data-type="photo" href=""><i class="icon ion-ios7-heart"></i> <span>{{($hasLike) ? trans('like.unlike') : trans('like.like')}}</span></a>
        @endif
    </ul>
    <ul class="pull-right nav">
        @if($photo->post_id and $photo->post)
        <li><i class="icon ion-thumbsup"></i><span class="photo-post-like-count-{{$photo->post->id}} post-like-count-{{$photo->id}}">{{$photo->post->countLikes()}}</span>  {{trans('like.likes')}}</li>
        <li><i class="icon ion-chatbox"></i> <span class="photo-post-reply-count-{{$photo->post->id}} post-reply-count-{{$photo->id}}">{{$photo->post->countComments()}}</span> {{trans('comment.comments')}}</li>
        @else

        <li><i class="icon ion-thumbsup"></i><span class="post-like-count-{{$photo->id}}">{{$photo->countLikes()}}</span>  {{trans('like.likes')}}</li>
        <li><i class="icon ion-chatbox"></i> <span class="post-reply-count-{{$photo->id}}">{{$photo->countComments()}}</span> {{trans('comment.comments')}}</li>

        @endif
     </ul>
</div>

@if($photo->post_id and $photo->post)
<div class="post-replies" id="photo-post-replies" data-limit="5" data-offset="5" data-type="post" data-type-id="{{$photo->post->id}}">
@else
<div class="post-replies" id="photo-post-replies" data-limit="5" data-offset="5" data-type="photo" data-type-id="{{$photo->id}}">
@endif
            @if(Auth::check())
                <?php
                    $param = [
                        'typeId' => $photo->id,
                        'type' => 'photo'
                    ];

                    if($photo->post_id and $photo->post) {
                        $param['typeId'] = $photo->post->id;
                        $param['type'] = 'post';
                        $param['uniqueId'] = 'photo-'.$photo->id;
                    }
                ?>
                @if($photo->page_id and $photo->page)
                    @if($photo->page->present()->isAdmin() or $photo->page->present()->isEditor())

                        <?php $param['avatar'] = $photo->page->present()->getAvatar(30)?>
                    @endif

                @endif

                {{Theme::section('comment.form', $param)}}
            @endif

            @if($photo->post_id and $photo->post)
                @if($photo->post->countComments() > 5)
                <a href="" class="load-more-comment" data-target="#photo-post-replies"><i class="icon ion-more"></i> View more comments <img class="indicator" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/></a>
                @endif
                <div id="photo-{{$photo->id}}-reply-lists" class="replies-list">

                    @foreach($photo->post->comments->take(5)->reverse() as $comment)

                        @if ($photo->post->type == 'page' and $photo->post->page->user->id == $comment->user->id)
                            {{Theme::section('comment.display-page', ['comment' => $comment, 'page' => $photo->post->page])}}
                        @else

                            {{Theme::section('comment.display', ['comment' => $comment])}}
                        @endif

                    @endforeach
                </div>
            @else
                @if($photo->countComments() > 5)
                <a href="" class="load-more-comment" data-target="#photo-post-replies"><i class="icon ion-more"></i> View more comments <img class="indicator" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/></a>
                @endif
                <div id="photo-{{$photo->id}}-reply-lists" class="replies-list">

                    @foreach($photo->comments->take(5)->reverse() as $comment)

                    {{Theme::section('comment.display', ['comment' => $comment])}}

                    @endforeach
                </div>
            @endif


</div>