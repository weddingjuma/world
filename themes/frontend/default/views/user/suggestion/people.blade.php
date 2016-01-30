@if(count($users))
    <div class="box">
        <div class="box-title">
            {{trans('user.people-you-know')}}
        </div>
        <div class="box-content">
            @foreach($users as $user)
                {{Theme::section('user.display', ['user' => $user])}}
            @endforeach

            {{$users->links()}}
        </div>
    </div>
@endif