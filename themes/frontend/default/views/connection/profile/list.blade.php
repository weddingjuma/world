<div style="margin-top: 20px" class="box">

    <div class="box-content">
        <ul class="nav nav-tabs">
          <li class="{{($type == 'friends') ? 'active' : null}}"><a data-ajaxify="true" href="{{$profileUser->present()->url('connection/friends')}}">{{trans('connection.friends')}}</a></li>
          <li class="{{($type == 'followers') ? 'active' : null}}"><a data-ajaxify="true" href="{{$profileUser->present()->url('connection/followers')}}">{{trans('connection.followers')}}</a></li>
          <li class="{{($type == 'following') ? 'active' : null}}"><a data-ajaxify="true" href="{{$profileUser->present()->url('connection/following')}}">{{trans('connection.following')}}</a></li>
        </ul>

        @foreach($connections as $connection)
            @if ($type == 'friends')
                <?php $user = $connection->present()->getfriend($profileUser->id)?>
                @if($user)
                    {{Theme::section('user.display', ['user' => $user])}}
                @endif
            @elseif($type == 'followers')

                @if($connection->fromUser)
                    {{Theme::section('user.display', ['user' => $connection->fromUser])}}
                @endif
            @elseif($type == 'following')
                @if($connection->toUser)
                    {{Theme::section('user.display', ['user' => $connection->toUser])}}
                @endif
            @endif
        @endforeach

        {{$connections->links()}}

    </div>
</div>