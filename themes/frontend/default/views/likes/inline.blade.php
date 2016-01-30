<h3 class="title">{{trans('like.people-like')}}</h3>

<div class="user-list">
    @foreach($likes as $like)

        {{Theme::section('user.display', ['user' => $like->user, 'mini' => true])}}
    @endforeach
</div>