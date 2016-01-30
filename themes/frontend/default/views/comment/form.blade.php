<div class="media clearfix" style="overflow: visible">
    <?php

    if (isset($uniqueId)) {
        $cloneId = $uniqueId;
    }
    $uniqueId = (isset($uniqueId)) ? $uniqueId : $typeId;
    ?>

    <div class="media-body" style="overflow: visible;content:'';

 display:block;">
        <form {{(isset($cloneId)) ? 'data-cloneid="'.$cloneId.'"' : null}} id="reply-form-{{$type}}-{{$uniqueId}}" data-type="{{$type}}" class="clearfix" data-id="{{$typeId}}" enctype="multipart/form-data" action="" method="post">
            <input data-type="{{$type}}" data-id="{{$uniqueId}}" id="{{$uniqueId}}-reply-input" autocomplete="off" data-target="#{{$typeId}}-hashtag-mention-suggestion" data-text-limit="{{Config::get('post-text-limit')}}" data-counter-target="#{{$typeId}}-text-counter" class="mention {{(Config::get('enable-post-text-limit')) ? 'post-text-limit' : null}}" type="text" placeholder="{{trans('comment.post-a-comment')}}"/>
            <input type="hidden" name="val[type]" value="{{$type}}"/>
            <input type="hidden" name="val[type_id]" value="{{$typeId}}"/>
            <div class="actions">

            </div>

            <div class="real-comment-form">
                <textarea data-height="30" style="height: 50px" id="{{$uniqueId}}-reply-textarea" data-target="#{{$uniqueId}}-hashtag-mention-suggestion" data-text-limit="{{Config::get('post-text-limit')}}" data-counter-target="#{{$uniqueId}}-text-counter" class="mention {{(Config::get('enable-post-text-limit')) ? 'post-text-limit' : null}}"  name="val[text]" placeholder="{{trans('comment.post-a-comment')}}" ></textarea>
                <div class="hashtag-mention-suggestion" id="{{$uniqueId}}-hashtag-mention-suggestion" style="position: relative">
                    <div class="listing"></div>
                </div>
                <div class="reply-footer">
                    <button class="btn btn-success btn-sm">{{trans('global.reply')}}</button>
                    <a data-type="{{$type}}" data-id="{{$uniqueId}}" href="" class="btn btn-danger btn-sm cancel-reply-form">{{trans('global.cancel')}}</a>


                    @if(Config::get('enable-emoticon'))

                    <a class="emoticon-selector" href="javascript:void(0)">
                        <img style="display: inline-block;width: 15px;margin: 3px auto" src="{{Theme::asset()->img('theme/images/emoticon.png')}}"/>
                    </a>
                    <div class="" style="display: none">

                        @foreach(Theme::option()->get('emoticons') as $code => $details)
                        <a title="{{$details['title']}}" data-target="#{{$uniqueId}}-reply-textarea" style="display: inline-block;margin: 5px" href="" data-code="{{$code}}" class="each-emoticon-selector"><img src="{{$details['image']}}"/> </a>
                        @endforeach
                    </div>

                    @endif
                        <span  class=" fileupload fileupload-exists" data-provides="fileupload">

                                  <a  class="btn-file btn">
                                      <span class="fileupload-new"><i class="icon ion-android-camera"></i></span>
                                      <span class="fileupload-exists"><i class="icon ion-android-camera"></i></span>
                                      <input title="" class="" type="file" name="image">
                                  </a>
                        </span>

                    @if(Config::get('enable-post-text-limit'))
                    <span style="margin:0" id="{{$uniqueId}}-text-counter" class="post-text-counter">{{Config::get('post-text-limit')}}</span>
                    @endif
                </div>
            </div>

        </form>

    </div>
</div>

