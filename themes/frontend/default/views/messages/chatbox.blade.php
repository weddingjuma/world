<div class="chatbox-container">
    <a class="opener" href=""><i class="icon ion-ios7-person"></i> {{trans('message.friends-online')}} (<span class="friends-online-count">{{app('App\\Repositories\\UserRepository')->countFriendsOnline()}}</span>)

    </a>
    <div class="dropdown chat-online-status clearfix">
        <span >{{trans('message.status')}} : <span class="online-status">
                <?php $status = \Auth::user()->present()->isOnline()?>
                @if($status == 1)
                    {{trans('message.online')}}
                @elseif($status == 2)
                    {{trans('message.busy')}}
                @else
                    {{trans('message.offline')}}
                @endif
        </span></span>
        <a data-toggle="dropdown"  href="" class="btn btn-xs btn-default dropdown-toggle pull-right">{{trans('message.change')}}</a>
        <ul class="dropdown-menu pull-right">
            <li><a data-value="1"  href="">{{trans('message.online')}}</a> </li>
            <li><a data-value="2" href="">{{trans('message.busy')}}</a> </li>
            <li><a data-value="0" href="">{{trans('message.offline')}}</a> </li>
        </ul>
    </div>
    <div class="chat-list">
        {{Theme::section('messages.online', [
        'users' => app('App\Repositories\UserRepository')->listOnlineUsers()
        ]);}}
    </div>
</div>