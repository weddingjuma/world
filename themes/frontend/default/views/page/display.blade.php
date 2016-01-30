<div class="media general-listing">

    <div class="media-object pull-left">
        <a data-ajaxify="true" class="cover" href="{{$page->present()->url()}}"><img src="{{$page->present()->getAvatar(600)}}"/> </a>
    </div>

    <div class="media-body">
        <h3 class="media-heading">
            <a data-ajaxify="true" class="cover" href="{{$page->present()->url()}}">{{$page->title}} </a>
             {{Theme::section('page.verified', ['page' => $page])}}</h3>

        <p>
            <i class="icon ion-thumbsup"></i> <span class="post-like-count-{{$page->id}}">{{$page->countLikes()}}</span> {{trans('like.likes')}}
        </p>

        {{Theme::extend('page-display', ['page' => $page])}}

        <?php $hasLike = $page->hasLiked()?>

        <a  data-is-login="{{Auth::check()}}" data-status="{{($hasLike) ? '1' : 0}}" class="btn btn-default btn-xs like-button" data-like="{{trans('like.like')}}" data-unlike="{{trans('like.unlike')}}" data-id="{{$page->id}}" data-type="page" href=""><i class="icon ion-ios7-heart"></i> <span>{{($hasLike) ? trans('like.unlike') : trans('like.like')}}</span></a>


    </div>
</div>