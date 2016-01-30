
<div class="box">
    <div class="box-title">{{trans('message.messages')}}</div>
    <div class="messages-container">
        <div data-lastcheck="" class="message-list-container" data-userid="{{$userid}}">
            <a href="" class="load-old-message" data-userid="{{$userid}}">Load old messages</a>
            @foreach($messages->reverse() as $message)
                {{Theme::section('messages.display', ['message' => $message])}}



            @endforeach
        </div>

        <div class="message-form-container">
            <form enctype="multipart/form-data" data-userid="{{$userid}}" action="" method="post">
                <input type="text" placeholder="{{trans('message.write-message')}}" name="text" class="form-control" id="message-textarea" />

                <button class="btn btn-sm btn-success" >{{trans('message.send')}}</button>
                @if(Config::get('enable-emoticon'))

                    <a class="emoticon-selector" href="javascript:void(0)">
                        <img style="display: inline-block;width: 25px;margin: 3px" src="{{Theme::asset()->img('theme/images/emoticon.png')}}"/>
                    </a>
                    <div class="" style="display: none">

                        @foreach(Theme::option()->get('emoticons') as $code => $details)
                        <a title="{{$details['title']}}" data-target="#message-textarea" style="display: inline-block;margin: 5px" href="" data-code="{{$code}}" class="each-emoticon-selector"><img src="{{$details['image']}}"/> </a>
                        @endforeach
                    </div>

                @endif

                <span style="position:relative;top: 15px; overflow: hidden;display: inline-block"   class=" fileupload fileupload-exists" data-provides="fileupload">

                    <a title="{{trans('post.attach-photos')}}"  class="btn-file">
                        <span class="fileupload-new"><i class="icon ion-android-camera"></i></span>
                        <span class="fileupload-exists"><i class="icon ion-android-camera"></i></span>

                        <input  id="post-image-input"  class="" type="file" name="image">
                    </a>
                </span>
            </form>
        </div>
    </div>
</div>