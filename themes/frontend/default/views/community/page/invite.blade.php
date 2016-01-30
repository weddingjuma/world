<div class="box">
    <div class="box-title">{{trans('community.invite-members')}}</div>
    <div class="box-content">
        @if(!count($connections))
            <div class="alert alert-danger">{{trans('community.invite-error')}}</div>
        @endif
        @foreach($connections as $connection)

             <?php $user = $connection->present()->getfriend(\Auth::user()->id)?>
            <div class=" user media ">
                  <div class="media-object pull-left">
                       <a data-url="{{$user->present()->popoverUrl()}}" class="user-popover" href="{{$user->present()->url()}}" data-ajaxify="true"><img src="{{$user->present()->getAvatar(100)}}"/></a>
                  </div>
                  <div class="media-body">
                      <h5 class="media-heading">{{$user->fullname}} {{Theme::section('user.verified', ['user' => $user])}} <span>@ {{$user->username}}</span> </h5>
                       @if(!isset($actions))
                            <div class="action-buttons">
                               <?php $status = $community->present()->memberStatus($user->id)?>
                               @if ($status == 'invited')
                                    <a href="" class="btn btn-default btn-xs" disabled="disabled">{{trans('community.invited')}}</a>
                               @elseif($status == 'member')
                                    <a href="" class="btn btn-default btn-xs" disabled="disabled">{{trans('community.already-member')}}</a>
                               @else
                                    <a href="{{URL::route('invite-member', ['type' => 'community', 'typeId' => $community->id, 'userid' => $user->id])}}" class="btn btn-success invite-member btn-xs" >{{trans('community.invite')}}</a>
                               @endif
                           </div>
                       @endif
                  </div>
            </div>
        @endforeach

        {{$connections->links()}}

    </div>
</div>