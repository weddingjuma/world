@if($conversation->present()->theUser())

<a  data-ajaxify="true" class="{{(isset($userid) and $userid == $conversation->present()->theUser()->id) ? 'active' : null}}" href="{{URL::route('messages')}}?user={{$conversation->present()->theUser()->username}}">
    <div class="media">
        <div class="media-object pull-left">
            <img src="{{$conversation->present()->theUser()->present()->getAvatar(100)}}"/>
        </div>
        <div class="media-body">
            <h3>
                {{$conversation->present()->theUser()->present()->fullName()}}

                <?php $count = app('App\\Repositories\\MessageRepository')->countUnreadFrom($conversation->present()->theUser()->id)?>
                @if($count)
                <strong style="font-size: 12px">({{$count}})</strong>
                @endif
            </h3>

        </div>
    </div>
</a>
@endif
