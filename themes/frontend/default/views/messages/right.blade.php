@if(count($conversations))
    <div class="box">
        <div class="box-title">{{trans('message.conversations')}}</div>
        <div class="message-conversation-list" style="max-height: 500px;overflow: auto">
            @foreach($conversations as $conversation)
                {{Theme::section('messages.conversation', ['conversation' => $conversation, 'userid' => $userid])}}
            @endforeach
        </div>
    </div>
@endif