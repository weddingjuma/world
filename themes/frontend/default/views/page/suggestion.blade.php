<?php $pages = app('App\\Repositories\\PageRepository')->suggest(2)?>

@if(count($pages))

<div class="box">
    <div class="box-title">{{trans('page.you-may-like')}}</div>
    <div class="box-content">

        @foreach($pages as $page)

        <div class=" user media media">
            <div class="media-object pull-left">
                <a   href="{{$page->present()->url()}}" data-ajaxify="true"><img src="{{$page->present()->getAvatar(150)}}"/></a>
            </div>
            <div class="media-body">
                <h5 class="media-heading">{{$page->title}} </h5>

                <p>
                    <i class="icon ion-thumbsup"></i> <span class="post-like-count-{{$page->id}}">{{$page->countLikes()}}</span> {{trans('like.likes')}}
                </p>
                <div class="action-buttons">
                    <?php $hasLike = $page->hasLiked()?>

                    <a  data-is-login="{{Auth::check()}}" data-status="{{($hasLike) ? '1' : 0}}" class="btn btn-default btn-xs like-button" data-like="{{trans('like.like')}}" data-unlike="{{trans('like.unlike')}}" data-id="{{$page->id}}" data-type="page" href=""><i class="icon ion-ios7-heart"></i> <span>{{($hasLike) ? trans('like.unlike') : trans('like.like')}}</span></a>

                </div>
            </div>
        </div>

        @endforeach

    </div>
</div>
@endif