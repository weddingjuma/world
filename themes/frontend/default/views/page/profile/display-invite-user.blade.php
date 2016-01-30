<div class=" user media user-mini">
    <div class="media-object pull-left">
        <a data-url="{{$user->present()->popoverUrl()}}" class="user-popover" href="{{$user->present()->url()}}" data-ajaxify="true">
            <img width="20" height="20" src="{{$user->present()->getAvatar(100)}}"/>
        </a>
    </div>
    <div class="media-body">
        <a  data-ajaxify="true" href="{{$user->present()->url()}}">
            <h5 class="media-heading">{{$user->fullname}} {{Theme::section('user.verified', ['user' => $user])}} </h5>
        </a>

        <div class="action-buttons">
            <?php $status = $page->present()->likeStatus($user->id)?>

            @if($status == 'liked')
                <a href="" class="btn btn-default btn-xs" disabled>{{trans('like.liked')}}</a>
            @elseif ($status == 'invited')
                <a href="" class="btn btn-default btn-xs" disabled>{{trans('invite.invited')}}</a>
            @else
            <a href="{{URL::route('invite-member', ['type' => 'page', 'typeId' => $page->id, 'userid' => $user->id])}}" class="btn btn-success invite-member btn-xs" >{{trans('community.invite')}}</a>
            @endif
        </div>

    </div>
</div>