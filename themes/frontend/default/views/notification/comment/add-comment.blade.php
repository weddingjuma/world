@if($type == 'post')
{{Theme::section('notification.comment.post', ['notification' => $notification, 'typeId' => $typeId, 'type' => $type])}}
@elseif($type == 'photo')
    {{Theme::section('notification.comment.photo', ['notification' => $notification, 'typeId' => $typeId, 'type' => $type])}}
@endif