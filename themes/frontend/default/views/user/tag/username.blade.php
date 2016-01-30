@foreach($users as $user)
    <a href="" data-tag="{{$user->present()->atName()}}"  class="media">
        <div class="media-object pull-left"><img width="20" height="20" src="{{$user->present()->getAvatar(20)}}"/> </div>
        <div class="media-body">
            <h4 class="media-heading"><strong>{{$user->fullname}}</strong> {{$user->present()->atName()}} {{Theme::section('user.verified', ['user' => $user])}}</h4>

        </div>
    </a>

@endforeach