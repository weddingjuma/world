@foreach($users as $user)
    <a href="" data-username="{{$user->present()->atName()}}" data-userid="{{$user->id}}" data-name="{{$user->fullname}}" class="media">
        <div class="media-object pull-left"><img width="20" height="20" src="{{$user->present()->getAvatar(20)}}"/> </div>
        <div class="media-body">
            <h4 class="media-heading">{{$user->fullname}} {{Theme::section('user.verified', ['user' => $user])}}</h4>

        </div>
    </a>

@endforeach