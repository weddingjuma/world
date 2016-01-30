{{Theme::extend('page-timeline-before-post-editor')}}

@if(Auth::check() and ($page->isOwner() or $page->present()->isAdmin() or $page->present()->isEditor()))
    {{Theme::section('post.editor.main', [
    'offPrivacy' => true,
    'type' => 'page',
    'page_id' => $page->id,
    'privacy' => 1,
    'hideAvatar' => true,
    'avatar' => $page->present()->getAvatar(50)
    ])}}
@endif

{{Theme::extend('page-timeline-after-post-editor')}}

<?php Theme::widget()->add('page.profile.timeline-widget', ['page-timeline-post'], ['pageId' => $page->id])?>
{{Theme::widget()->get('page-timeline-post')}}
