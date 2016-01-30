@foreach($hashtags as $hashtag)
    <a href="{{$hashtag->url()}}" data-ajaxify="true">{{$hashtag->hash}}</a>
@endforeach

@foreach($users as $user)
{{Theme::section('user.display', ['user' => $user, 'mini' => true, 'actions' => false])}}
@endforeach

@foreach($pages as $page)
    <div class=" user media user-mini">
        <div class="media-object pull-left">
            <a  href="{{$page->present()->url()}}" data-ajaxify="true"><img src="{{$page->present()->getAvatar(100)}}"/></a>
        </div>
        <div class="media-body">
            <h5 class="media-heading">{{$page->title}} {{Theme::section('page.verified', ['page' => $page])}} </h5>
            <p>
                <i class="icon ion-thumbsup"></i> <span class="post-like-count-{{$page->id}}">{{$page->countLikes()}}</span> Likes
            </p>
            <div class="action-buttons">
                <?php $hasLike = $page->hasLiked()?>

                <a  data-is-login="{{Auth::check()}}" data-status="{{($hasLike) ? '1' : 0}}" class="btn btn-default btn-xs like-button" data-like="Like" data-unlike="Unlike" data-id="{{$page->id}}" data-type="page" href=""><i class="icon ion-ios7-heart"></i> <span>{{($hasLike) ? 'Unlike' : 'Like'}}</span></a>

            </div>

        </div>
    </div>
@endforeach

@foreach($communities as $community)
    <div class=" user media user-mini">
        <div class="media-object pull-left">
            <a  href="{{$community->present()->url()}}" data-ajaxify="true"><img src="{{$community->present()->getLogo(100)}}"/></a>
        </div>
        <div class="media-body">
            <h5 class="media-heading">{{$community->title}} {{Theme::section('community.verified', ['community' => $community])}} </h5>
            <p>
                {{count($community->members) + 1}} Members | {{count($community->posts)}} Posts
            </p>
            <div class="action-buttons">

            </div>

        </div>
    </div>

@endforeach

@foreach($games as $game)
<div class=" user media user-mini">
    <div class="media-object pull-left">
        <a  href="{{$game->present()->url()}}" data-ajaxify="true"><img src="{{$game->present()->getAvatar(100)}}"/></a>
    </div>
    <div class="media-body">
        <h5 class="media-heading">{{$game->title}} {{Theme::section('game.verified', ['game' => $game])}} </h5>

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