@if(Auth::check())
    <?php $loggedInUser = \Auth::user();
    $connectionRepository = app('App\\Repositories\\ConnectionRepository');

    ?>
    @if ($loggedInUser->id != $user->id)

        @if($user->present()->canFollowMe($loggedInUser))
            <?php $isFollowing = $connectionRepository->isFollowing($loggedInUser->id, $user->id)?>
            <a
                data-userid="{{$loggedInUser->id}}"
                data-touserid="{{$user->id}}"
                href=""
                data-unfollow-title = "{{trans('connection.unfollow')}}"
                data-follow-title="{{trans('connection.follow')}}"
                class="btn btn-xs {{$user->id}}-follow-button  {{(!$isFollowing) ? 'btn-lightblue follow-button ' : 'btn-danger unfollow-button '}}"
            >
                        <i class="icon ion-ios7-plus-outline"></i>
                        @if($isFollowing)
                            <span>{{trans('connection.unfollow')}}</span>
                        @else
                            <span>{{trans('connection.follow')}}</span>
                        @endif
            </a>

        @endif

        <?php list($friendStatus, $connection) = $connectionRepository->friendStatus($loggedInUser->id, $user->id)?>

        @if($user->present()->privacy('turnoff-friend-request', 0) != 1)
            @if($friendStatus != 2)

            <a
                href="{{($friendStatus == 1 and ($connection->to_user_id == Auth::user()->id)) ? URL::route('friend-requests') : null}}"

                data-userid="{{$loggedInUser->id}}"
                data-touserid="{{$user->id}}"
                data-sent-title="{{trans('connection.friend-request-sent')}}"
                class="btn btn-xs {{$user->id}}-add-friend-button {{($friendStatus == 1 and ($connection->to_user_id == Auth::user()->id))?'':'add-friend-button'}} {{($friendStatus == 0) ? 'btn-blue' : 'btn-default'}}"
            {{($friendStatus == 1 and ($connection->to_user_id == Auth::user()->id)) ? 'data-ajaxify="true"' : null}}
            >

            @if($friendStatus == 0)
            <i class="icon ion-ios7-personadd-outline"></i> <span>{{trans('connection.add-friend')}}</span>
            @else
            @if($connection->to_user_id == Auth::user()->id)
            {{trans('connection.respond-request')}}
            @else
            {{trans('connection.friend-request-sent')}}
            @endif
            @endif

            </a>

            @else

            <a class="btn btn-danger btn-xs unfriend-button" href="{{URL::route('connection-remove', ['userid' => $loggedInUser->id, 'touserid' => $user->id, 'way' => 2])}}">{{trans('connection.remove-friend')}}</a>
            @endif
        @endif
    @else
        <a href="javascript:void(0)" class="btn btn-xs btn-default">{{trans('connection.thats-you')}}</a>
    @endif
@endif