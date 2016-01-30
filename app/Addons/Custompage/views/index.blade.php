<div class="box">
    <div class="box-title">{{$page->title}}</div>
    <div class="box-content">
        <div class="custompage-content">{{$page->content}}</div>

        <div class="custom-page-footer-stats">
            <ul>
                @if($page->show_comments)
                    <li><span class="custompage-reply-count-{{$page->id}}">{{$page->countComments()}}</span> {{trans('comment.comments')}}</li>
                @endif

                @if($page->show_likes)
                    <li>
                        <span class="post-like-count-{{$page->id}}">{{$page->countLikes()}}</span>

                        <?php $hasLike = $page->hasLiked()?>

                        <a data-is-login="{{Auth::check()}}" data-status="{{($hasLike) ? '1' : 0}}" class="like-button" data-like="{{trans('like.like')}}" data-unlike="{{trans('like.unlike')}}" data-id="{{$page->id}}" data-type="custompage" href=""><i class="icon ion-ios7-heart"></i> <span>{{($hasLike) ? trans('like.unlike') : trans('like.like')}}</span></a>
                    </li>
                @endif
            </ul>
        </div>

        @if($page->show_comments)
        <div class="post-replies" id="{{$page->id}}-post-replies" data-limit="10" data-offset="3" data-type="custompage" data-type-id="{{$page->id}}">

            @if ($page->countComments() > 10)
            <a style="" href="" class="load-more-comment" data-target="#{{$post->id}}-post-replies">
                <i class="icon ion-chatbox"></i> {{trans('comment.view-more-comment')}} <img class="indicator" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/>
            </a>
            @endif

            <br/>
            @if(Auth::check())

            {{Theme::section('comment.form', ['typeId' => $page->id, 'type' => 'custompage'])}}

            @endif

            <div id="custompage-{{$page->id}}-reply-lists" class="replies-list">

                @foreach($page->comments->take(10)->reverse() as $comment)


                {{Theme::section('comment.display', ['comment' => $comment])}}

                @endforeach
            </div>

        </div>
        @endif
    </div>
</div>