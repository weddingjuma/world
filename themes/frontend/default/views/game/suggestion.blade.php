<?php $games = app('App\\Repositories\\GameRepository')->suggest(2)?>

@if(count($games))

<div class="box">
    <div class="box-title">Latest Games</div>
    <div class="box-content">

        @foreach($games as $game)

        <div class=" user media media">
            <div class="media-object pull-left">
                <a   href="{{$game->present()->url()}}" data-ajaxify="true"><img src="{{$game->present()->getAvatar(150)}}"/></a>
            </div>
            <div class="media-body">
                <h5 class="media-heading">{{$game->title}} </h5>

                <p>
                    <i class="icon ion-thumbsup"></i> <span class="post-like-count-{{$game->id}}">{{$game->countLikes()}}</span> Likes
                </p>
                <div class="action-buttons">
                    <?php $hasLike = $game->hasLiked()?>

                    <a  data-is-login="{{Auth::check()}}" data-status="{{($hasLike) ? '1' : 0}}" class="btn btn-default btn-xs like-button" data-like="Like" data-unlike="Unlike" data-id="{{$game->id}}" data-type="game" href=""><i class="icon ion-ios7-heart"></i> <span>{{($hasLike) ? 'Unlike' : 'Like'}}</span></a>

                </div>
            </div>
        </div>

        @endforeach

    </div>
</div>
@endif