@if($community->present()->canPost())
    {{Theme::section('post.editor.main', [
        'offPrivacy' => true,
        'type' => 'community',
        'community_id' => $community->id,
        'typeId' => $typeId,
        'privacy' => (($community->privacy == 1) ? 1 : 6)
    ])}}
@endif

<div data-type="communitycategory-{{$typeId}}" data-offset=""  id="post-list">

    @foreach($posts as $post)
        {{Theme::section('post.media', ['post' => $post])}}
    @endforeach
</div>

