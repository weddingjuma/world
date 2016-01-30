<div id="editor" class="">

    <form enctype="multipart/form-data" id="post-form" class="form-horizontal"  action="" method="post">
        <input type="hidden" value="{{(isset($type)) ? $type : 'user-timeline'}}" name="val[type]" />
        <input type="hidden" value="{{(isset($typeId)) ? $typeId : ''}}" name="val[type_id]" />
        <input type="hidden" value="{{(isset($community_id)) ? $community_id : ''}}" name="val[community_id]" />
        <input type="hidden" value="{{(isset($page_id)) ? $page_id : ''}}" name="val[page_id]" />
        <input type="hidden" value="text" id="content_type" name="val[content_type]"/>
        @if(isset($to))
            <input type="hidden" value="{{$to}}" name="val[to_userid]"/>
        @endif
        <div class="editor-header">
            <ul class="nav clearfix">
                <li><a class="current status" href="">{{trans('post.status')}}</a> </li>

                <li   class=" fileupload fileupload-exists" data-provides="fileupload">

                    <a  id="post-editor-photos-selector" title="{{trans('post.attach-photos')}}"  class="photo btn-file">
                        <span class="fileupload-new">{{trans('post.add-photos')}}</span>
                        <span class="fileupload-exists">{{trans('post.add-photos')}}</span>

                        <input accept="image/*"  id="post-image-input" multiple  class="" type="file" name="image[]">
                    </a>
                </li>

                <li>
                    <a class="content-type-toggle video" data-type="video" title="{{trans('post.share-video-from')}}" data-placeholder="{{trans('post.share-video-from')}}" href="">
                        {{trans('post.share-videos')}}
                    </a>
                </li>

                <li>
                    <a href="" class="post-add-file-trigger">{{trans('post.share-file')}}</a>
                </li>
                {{Theme::extend('post-editor-header-nav')}}

            </ul>

            <p style="position: absolute;top: 10px;right: 10px;display: none;font-weight:bold" id="post-editor-uploading-indicator">{{trans('post.uploading')}}<span></span></p>
            <img class="post-editor-indicator" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/>

        </div>

        <div class="editor-body">
            <div class="media post-textarea-container">
                <div class="media-object pull-left">
                    <img style="width: 50px !important;height: 50px !important;" src="{{(isset($avatar)) ? $avatar : $loggedInUser->present()->getAvatar(50)}}" width="50" height="50"/>
                </div>
                <div class="media-body">
                    <textarea id="post-textarea" data-text-limit="{{Config::get('post-text-limit')}}" data-counter-target="#main-editor-text-counter" data-target="#hashtag-mention-suggestion"   class="post-textarea mention {{(Config::get('enable-post-text-limit')) ? 'post-text-limit' : null}}" name="val[text]" placeholder="{{trans('post.share-what-new')}}"></textarea>
                </div>
            </div>

            <div class="add-file-container">
                <div class="wrap">
                    <div class="title">{{trans('post.from-your-computer')}}</div>
                    <div class="content">

                        <input id="post-editor-file-upload" type="file" name="share_file"/>

                        <span>({{trans('post.max-size')}}: {{ formatBytes(Config::get('max-upload-files'))}})</span>
                    </div>
                </div>
            </div>

            <div id="hashtag-mention-suggestion" class="hashtag-mention-suggestion">
                <div class="listing"></div>
            </div>
            <div id="post-type-content">
                  <div class="images-container"></div>

                  <input type="text" autocomplete="off"  name="val[type_content]" placeholder="{{trans('post.enter-video-url')}}"/>
                  <div class="post-media-suggestion">
                        <div class="search-indicator">


                        </div>
                        <div class="listing"></div>
                  </div>
                <div id="post-editor-video-upload-container" class=" fileupload fileupload-exists" data-provides="fileupload">
                    <a class="btn-file btn-success" style="padding:0px 5px">
                        <span class="fileupload-new"><i class="icon ion-ios7-cloud-upload-outline"></i> {{trans('post.upload-videos')}}</span>
                        <span class="fileupload-exists"><i class="icon ion-ios7-cloud-upload-outline"></i> {{trans('post.upload-videos')}}</span>

                        <input accept="video/mp4"  id="post-editor-video-upload" multiple  class="" type="file" name="video">
                    </a>

                    <span class="size-indicator">{{trans('post.video-max-size')}}: {{formatBytes(Config::get('max-size-upload-video', 10000000))}}</span>
                </div>
            </div>
            <div class="post-with-friend people-tagging">

                <div class="tags">
                    <div class="indicator"><img src="{{Theme::asset()->img('theme/images/loading.gif')}}" /></div>
                    <b>With : </b><input type="text" placeholder="{{trans('post.who-you-with')}}"/>
                </div>
                <div  class="people-suggestion-container">


                    <div class="suggestion-list"></div>
                    <!--<div class="suggestion-info">Click <a href="">here</a> if the name is not listed</div>-->
                </div>
            </div>

            <div class="post-editor-link-container">
                    <input type="hidden" value="" name="val[link_title]" class="link_title"/>
                    <input type="hidden" value="" name="val[the_link]" class="the_link"/>
                    <input type="hidden" name="val[link_description]" class="link_description"/>
                    <input type="hidden" name="val[link_image]" class="link_image"/>
                    <div class="link-preview-container">

                    </div>
            </div>
        </div>
        <div class="alert alert-danger" id="post-error" style="border-radius: 0;padding: 7px 10px;margin: 0;display:none">{{trans('post.error', ['size' => formatBytes()])}}</div>

        <div class="editor-footer">
            <ul class="nav">

                <li>
                    <a href="" class="add-people"><i class="ion-person-add icon"></i></a>
                </li>
                <li>
                    <a class="content-type-toggle" data-type="movie" title="{{trans('post.share-watched-movie')}}" data-placeholder="{{trans('post.share-watched-movie')}}" href="">
                    <i class="icon ion-ios7-film"></i>

                    </a> </li>
                <li>
                    <a class="content-type-toggle" data-type="audio" title="{{trans('post.share-music-from')}}" data-placeholder="{{trans('post.share-music-from')}}" href="">
                    <i class="ion-ios7-musical-notes"></i>

                    </a></li>
                <li><a class="content-type-toggle" data-type="location" title="{{trans('post.add-visited-place')}}" data-placeholder="{{trans('post.add-visited-place')}}" href="">
                <i class="icon ion-ios7-location-outline"></i>

                </a> </li>
                <li>
                    <a class="content-type-toggle" data-type="link" title="{{trans('post.share-link')}}" data-placeholder="{{trans('post.share-link')}}" href="">
                        <i class="icon ion-link"></i>
                    </a>
                </li>
                @if(Config::get('enable-emoticon'))
                    <li>
                    <a style="top: 5px" class="emoticon-selector" href="javascript:void(0)">
                        <img style="display: block;width: 25px;margin: 3px auto" src="{{Theme::asset()->img('theme/images/emoticon.png')}}"/>

                    <div class="" style="display: none">

                        @foreach(Theme::option()->get('emoticons') as $code => $details)
                              <a title="{{$details['title']}}" data-target="#post-textarea" style="display: inline-block;margin: 5px" href="" data-code="{{$code}}" class="each-emoticon-selector"><img src="{{$details['image']}}"/> </a>
                        @endforeach
                    </div>
                    </li>
                @endif

                {{Theme::extend('post-editor-bottom-nav')}}
                <li class="action-container">
                    <img class="indicator" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/>
                    @if(Config::get('enable-post-text-limit'))
                        <span id="main-editor-text-counter" class="post-text-counter">{{Config::get('post-text-limit')}}</span>
                    @endif
                    <div id="post-privacy-container" class="btn-group">

                        @if (!isset($to) and !isset($offPrivacy))
                            <button id="post-editor-privacy-selector" class="btn btn-default btn-sm dropdown-toggle" title="{{trans('post.who-see-this')}}" data-toggle="dropdown"><i class="icon ion-locked"></i> <span>{{Auth::user()->present()->postPrivacyName()}}</span> <i class="caret"></i></button>
                            <ul class="dropdown-menu pull-left">
                                <li><a data-value="1" data-text="{{trans('global.public')}}" href="">{{trans('global.public')}}</a> </li>
                                <li><a data-value="2" data-text="{{trans('connection.friends')}}" href="">{{trans('connection.friends')}}</a> </li>
                                <li><a data-value="3" data-text="{{trans('connection.followers')}}" href="">{{trans('connection.followers')}}</a> </li>
                                <li><a data-value="4" data-text="{{trans('connection.friends-followers')}}" href="">{{trans('connection.friends-followers')}}</a> </li>
                                <li><a data-value="5" data-text="{{trans('connection.only-me')}}" href="">{{trans('connection.only-me')}}</a> </li>


                            </ul>



                        @endif
                        <input type="hidden" value="{{(isset($privacy)) ? $privacy : Auth::user()->present()->postPrivacyValue()}}" name="val[privacy]"/>
                    </div>
                    <button id="post-submit-button" class="btn btn-sm btn-success"><i class="icon ion-compose"></i> {{trans('post.post')}}</button>

                </li>

            </ul>
        </div>
    </form>
</div>

<!-- -->