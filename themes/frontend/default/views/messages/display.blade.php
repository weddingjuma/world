@if($message->present()->canView())

<div class="media" id="message-{{$message->id}}">
    <div class="media-object pull-left">
        <a href="{{$message->senderUser->present()->url()}}"><img src="{{$message->senderUser->present()->getAvatar(200)}}"/></a>
    </div>
    <div class="media-body">
        <a href="" class="delete-message-button" data-id="{{$message->id}}"><i class="icon ion-close"></i></a>
        <h4>{{$message->senderUser->present()->fullName()}}</h4>
        <p>
            {{$message->present()->text()}}
        </p>

        @if($message->image)
            <div >
                <a class="preview-image" rel="message-{{$message->id}}" href="{{Image::url($message->image, 600)}}">
                    <img style="max-width: 40%;margin-bottom: 10px" src="{{Image::url($message->image, 600)}}">
                </a>
            </div>
        @endif

        <span class="post-time"><i class="icon ion-ios7-time-outline"></i> <span title="{{$message->present()->time()}}">{{formatDTime($message->created_at)}}</span></span>

    </div>
</div>
@endif