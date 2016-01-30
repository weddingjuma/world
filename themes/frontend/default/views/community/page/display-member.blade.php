            <div class=" user media ">
                  <div class="media-object pull-left">
                       <a data-url="{{$user->present()->popoverUrl()}}" class="user-popover" href="{{$user->present()->url()}}" data-ajaxify="true"><img src="{{$user->present()->getAvatar(100)}}"/></a>
                  </div>
                  <div class="media-body">
                      <h5 class="media-heading">{{$user->fullname}} {{Theme::section('user.verified', ['user' => $user])}} <span>{{$user->present()->atName()}}</span> </h5>

                                @if($community->present()->canManage())
                                    @if(\Auth::user()->id != $user->id and $user->id != $community->user_id)
                                        <a href="{{URL::route('leave-community', ['id' => $community->id])}}?userid={{$user->id}}">{{trans('global.remove')}}</a>
                                    @endif

                                    @if($user->id != $community->user_id and $community->isOwner())
                                       |
                                       @if($community->present()->isModerator($user->id))
                                            <a href="{{URL::route('remove-moderator', ['id' => $community->id, 'userid' => $user->id])}}">{{trans('community.remove-mod')}}</a>
                                       @else
                                            <a href="{{URL::route('assign-moderator', ['id' => $community->id, 'userid' => $user->id])}}">{{trans('community.assign-mod')}}</a>
                                       @endif
                                    @endif
                               @endif
                       @if(!isset($actions))
                            <div class="action-buttons">

                               {{Theme::section('connection.buttons', ['user' => $user])}}
                           </div>
                       @endif
                  </div>
            </div>