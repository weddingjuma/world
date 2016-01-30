@foreach($users as $user)
    <a
        data-user-id="{{$user->id}}"
        data-fullname="{{$user->fullname}}"
        data-avatar = "{{$user->present()->getAvatar(50)}}"
        href="">
        <div class="media">
            <div class="media-object pull-left">
                <img src="{{$user->present()->getAvatar(30)}}" width="30" height="30"/>
            </div>
            <div class="media-body">
                <h5 class="media-heading">{{$user->present()->fullName}}</h5>
            </div>
        </div>
    </a>
@endforeach