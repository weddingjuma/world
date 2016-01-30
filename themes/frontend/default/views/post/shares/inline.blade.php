<h3 class="title">{{trans('post.people-share-this')}}</h3>

<div class="user-list">
    @foreach($posts as $post)

        {{Theme::section('user.display', ['user' => $post->user, 'mini' => true])}}
    @endforeach
</div>