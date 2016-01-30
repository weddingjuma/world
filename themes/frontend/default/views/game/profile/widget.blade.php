
<div class="box">

    <div class="box-title">

        About {{$game->title}}

        @if(\Auth::check() and ($game->isOwner() or \Auth::user()->isAdmin()))
            <span class="pull-right">
                <a href="{{$game->present()->url('edit')}}">{{trans('global.edit')}}</a> | <a href="">{{trans('global.delete')}}</a>
            </span>
        @endif


    </div>
    <div class="box-content">
        <?php $hasLike = $game->hasLiked()?>

        <a  data-is-login="{{Auth::check()}}" data-status="{{($hasLike) ? '1' : 0}}" class="btn btn-default btn-xs like-button" data-like="{{trans('like.like')}}" data-unlike="{{trans('like.unlike')}}" data-id="{{$game->id}}" data-type="game" href=""><i class="icon ion-ios7-heart"></i> <span>{{($hasLike) ? trans('like.unlike') : trans('like.like')}}</span></a>

        <div class="page-like">
            <i class="icon ion-thumbsup"></i> <span class="post-like-count-{{$game->id}}">{{$game->countLikes()}}</span> {{trans('like.likes')}}
        </div>

        <table class="table table-striped">

            <tbody>

            <tr>
                <td><strong>{{trans('user.date-created')}} :</strong></td>
                <td><span class="post-time" ><span title="{{$game->present()->joinedOn()}}">{{$game->created_at}}</span></span> </td>
            </tr>


            <tr>
                <td><strong>{{trans('global.about')}}</strong></td>
                <td>{{$game->description}}</td>
            </tr>



            @foreach($game->present()->fields() as $field)
            <tr>
                <td><strong>{{$field->name}}</strong></td>
                <td>{{$game->present()->field($field->id)}}</td>
            </tr>
            @endforeach
            </tbody>

        </table>

    </div>
</div>


@if(Auth::check())
<?php $friendsLikes = $game->friendsLiked()?>
@if(count($friendsLikes) > 0)
<div class="box">
    <div class="box-title">{{trans('user.friend-like-this')}}</div>
    <div class="box-content">
        <div class="user-tile-list">

            @foreach($friendsLikes as $like)

            <a data-ajaxify="true" href="{{$like->user->present()->url()}}"><img src="{{$like->user->present()->getAvatar(100)}}"/> </a>

            @endforeach

        </div>
    </div>
</div>
@endif

@endif

@if($game->countLikes() > 0)
<div class="box">
    <div class="box-title">{{trans('user.people-like-this')}}</div>
    <div class="box-content">
        <div class="user-tile-list">
            @foreach($game->likes->take(12) as $like)

            <a data-ajaxify="true" href="{{$like->user->present()->url()}}"><img src="{{$like->user->present()->getAvatar(100)}}"/> </a>

            @endforeach
        </div>
    </div>
</div>
@endif