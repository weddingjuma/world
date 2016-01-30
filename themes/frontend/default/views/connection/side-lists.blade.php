@if($user->countFriends())
    <div class="box">
        <div class="box-title">
            {{trans('connection.friends')}} ({{$user->countFriends()}})
            <a href="{{$profileUser->present()->url('connection/friends')}}" class="pull-right"><i class="icon ion-more"></i> {{trans('global.view-all')}}</a>
        </div>
        <div class="box-content">

            <div class="user-tile-list">
                @foreach($user->friends(8) as $friend)
                    <?php $friend = $friend->present()->getFriend($user->id)?>
                    @if($friend)
                        <a data-ajaxify="true" href="{{$friend->present()->url()}}"><img src="{{$friend->present()->getAvatar(100)}}"/> </a>
                    @endif
                @endforeach

            </div>

        </div>
    </div>
@endif

<?php $followers = $user->followers(4)?>
@if(count($followers))
    <div class="box">
        <div class="box-title">
            {{trans('connection.followers')}}
            <a href="{{$profileUser->present()->url('connection/followers')}}" class="pull-right"><i class="icon ion-more"></i> {{trans('global.view-all')}}</a>
        </div>
        <div class="box-content">

            <div class="user-tile-list">
                @foreach($followers as $follower)

                    @if($follower->fromUser)
                            <a data-ajaxify="true" href="{{$follower->fromUser->present()->url()}}"><img src="{{$follower->fromUser->present()->getAvatar(100)}}"/> </a>
                    @endif
                @endforeach

            </div>

        </div>
    </div>
@endif

<?php $followings = $user->following(4)?>
@if(count($followings))
    <div class="box">
        <div class="box-title">
            {{trans('connection.following')}}
            <a href="{{$profileUser->present()->url('connection/following')}}" class="pull-right"><i class="icon ion-more"></i> {{trans('global.view-all')}}</a>
        </div>
        <div class="box-content">

            <div class="user-tile-list">
                @foreach($followings as $followedUser)

                    @if($followedUser->toUser)
                <a data-ajaxify="true" href="{{$followedUser->toUser->present()->url()}}"><img src="{{$followedUser->toUser->present()->getAvatar(100)}}"/> </a>
                    @endif
                @endforeach

            </div>

        </div>
    </div>
@endif