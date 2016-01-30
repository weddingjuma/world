@if(Auth::check())
    @if($profileUser->isOwner())
        {{Theme::section('post.editor.main')}}
    @elseif($profileUser->present()->canPost())
        {{Theme::section('post.editor.main', ['to' => $profileUser->id, 'privacy' => 1])}}
    @endif
@endif
<?php Theme::widget()->add('profile.timeline-widget', ['user-profile-timeline'], ['userId' => $profileUser->id])?>
{{Theme::widget()->get('user-profile-timeline')}}
