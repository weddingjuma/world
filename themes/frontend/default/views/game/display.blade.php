<div class="general-listing media">

    <div class="media-object pull-left">
        <a data-ajaxify="true" class="cover" href="{{$game->present()->url()}}"><img src="{{$game->present()->getAvatar(600)}}"/> </a>
    </div>

    <div class="media-body">

        <h3 class="media-heading">
            <a data-ajaxify="true" class="cover" href="{{$game->present()->url()}}">{{$game->title}}</a>
             {{Theme::section('game.verified', ['game' => $game])}}
        </h3>
        <span><i class="icon ion-game-controller-b"></i> {{trans('game.game')}}</span>

        <p>
            <i class="icon ion-thumbsup"></i> <span class="post-like-count-{{$game->id}}">{{$game->countLikes()}}</span> {{trans('like.likes')}}
        </p>

        <?php $hasLike = $game->hasLiked()?>

        <a  data-is-login="{{Auth::check()}}" data-status="{{($hasLike) ? '1' : 0}}" class="btn btn-default btn-xs like-button" data-like="{{trans('like.like')}}" data-unlike="{{trans('like.unlike')}}" data-id="{{$game->id}}" data-type="game" href=""><i class="icon ion-ios7-heart"></i> <span>{{($hasLike) ? trans('like.unlike') : trans('like.like')   }}</span></a>


    </div>
</div>