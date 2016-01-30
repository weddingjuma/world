@foreach($messages->reverse() as $message)
    {{Theme::section('messages.display', ['message' => $message])}}
@endforeach