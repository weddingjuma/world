<a
    data-userid = "{{$message->senderUser->id}}"
    data-link = "{{$message->senderUser->present()->url()}}"
    data-name = "{{str_limit($message->senderUser->present()->fullName(), 100)}}"
    class="message-dropdown-link"
    href="{{URL::route('messages')}}?user={{$message->senderUser->username}}">
    <div class="media">
        <div class="media-object pull-left">
            <img src="{{$message->senderUser->present()->getAvatar(100)}}"/>
        </div>
        <div class="media-body">
            <h3>
                {{$message->senderUser->present()->fullName()}}
                <?php $count = app('App\\Repositories\\MessageRepository')->countUnreadFrom($message->sender)?>
                @if($count)
                    <strong style="font-size: 12px">({{$count}})</strong>
                @endif
            </h3>
            <?php $lastMessage = app('App\\Repositories\\MessageRepository')->lastMessage($message->sender)?>
            <p>{{\Hook::fire('post-text', str_limit($lastMessage->text, 30))}}</p>
            <span class="post-time"><i class="icon ion-ios7-time-outline"></i> <span title="{{$message->present()->time()}}">{{formatDTime($message->created_at)}}</span></span>

        </div>
    </div>
</a>