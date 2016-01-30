@if(!count($users))
    <p>{{trans('message.no-friends')}}</p>
@endif

@foreach($users as $user)
    <a
        data-userid = "{{$user->id}}"
        data-link = "{{$user->present()->url()}}"
        data-name = "{{str_limit($user->present()->fullName(), 100)}}"
        href="{{URL::route('messages')}}?user={{$user->username}}">
        <div class="media">
            <div class="media-object pull-left">
                <img src="{{$user->present()->getAvatar(50)}}"/>
            </div>
            <div class="media-body">
                <h4>{{$user->fullname}} {{Theme::section('user.verified', ['user' => $user])}}</h4>

            </div>
        </div>
    </a>
@endforeach