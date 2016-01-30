@if(count($messages))
    <div class="message-conversation-list">
        @foreach($messages as $message)
        {{Theme::section('messages.display-dropdown', ['message' => $message])}}
        @endforeach
    </div>
@else
    {{trans('message.no-new-message')}}
@endif