<?php $details = $post->present()->getAutoPost();?>
<i class="icon ion-ios7-arrow-thin-right"></i>
@if($details['type'] == 'change-avatar')
    {{trans('post.change-profile-photo')}}
@elseif($details['type'] == 'add-photos')
     {{trans('post.added-photos', ['count' => count($post->present()->getAutoPostPhotos())])}}
@elseif($details['type'] == 'like-page' and $details['page'])
    {{trans('like.liked')}} <a href="{{$details['page']->present()->url()}}">{{$details['page']->title}}</a>
@elseif($details['type'] == 'like-game' and $details['game'])
{{trans('like.liked')}} <a href="{{$details['game']->present()->url()}}">{{$details['game']->title}}</a>
@endif