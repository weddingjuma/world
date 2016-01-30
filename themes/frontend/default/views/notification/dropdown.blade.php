@foreach($notifications as $notification)
{{$notification->present()->render()}}
@endforeach