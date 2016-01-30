@if($post->user and $post->present()->isGood())


<div id="post-{{$post->id}}" {{$post->type}} {{$post->content_type}} {{$post->page_id}} {{$post->community_id}}  class="post-media user-post-{{$post->user->id}} clearfix">
    {{Theme::section('post.header', ['post' => $post])}}
    <!--original owner of post incase of shared-->
    @if(!empty($post->shared) and $post->sharedUser)
    <div class="media original-poster">
        <div class="media-object pull-left"><img src="{{$post->sharedUser->present()->getAvatar(50)}}"/> </div>
        <div class="media-body">
            <h5 class="media-heading">{{$post->sharedUser->fullname}} <span>{{$post->sharedUser->present()->atName()}}</span> {{trans('post.originally-post-this')}}</h5>
        </div>
    </div>
    @endif

    <div class="post-body">


        <div id="post-text-content-{{$post->id}}" class="post-text-content">

            @if($post->present()->hasTooMuchText())
            {{$post->present()->text(Str::limit($post->text, Config::get('post-text-max-show')))}}
            <div class="hidden" id="full-text-content-{{$post->id}}">{{$post->present()->text()}}</div>
            <a href="" class="post-read-more" data-id="{{$post->id}}" data-content="">{{trans('post.read-more')}}</a>
            @else
            {{$post->present()->text()}}
            @endif

            {{Theme::extend('inline-post-text', ['post' => $post])}}

        </div>


        @if($post->present()->canDelete())
        <div id="post-inline-editor-{{$post->id}}" class="post-inline-editor clearfix">
            <form  action="" method="post">
                <textarea name="text" >{{$post->text}}</textarea>
                <div class="pull-right">
                    <button data-text="{{$post->text}}" data-edited="{{trans('post.edited')}}" data-id="{{$post->id}}" class="save-post-inline-editor btn btn-success btn-xs">{{trans('post.done-editing')}}</button>
                    <a data-id="{{$post->id}}" class="cancel-post-inline-editor btn btn-default btn-xs">{{trans('post.cancel')}}</a>
                </div>
            </form>
        </div>
        @endif

        @if($post->present()->isAutoPost())
        {{Theme::section('post.auto-post.body', ['post' => $post])}}
        @endif


        @if($post->content_type == 'image')
        <?php $images = $oImages = $post->present()->images()?>
        <div class="render-post-images" >
            <?php if(count($images) == 1):?>
                <div class="one-image">
                    @if(!empty($images[0]))
                        <a class="preview-image" rel="{{$post->id}}-images" href="{{Image::url($images[0], '960')}}"><img src="{{Image::url($images[0], 600)}}"/></a>
                    @endif
                </div>
            <?php elseif(count($images) == 2):?>
                <div class="two-images clearfix">
                    <?php foreach($images as $image):?>
                        @if(!empty($image))
                            <a class="preview-image" href="{{Image::url($image, '960')}}" rel="{{$post->id}}-images" style="background-image:url({{Image::url($image, 600)}})"></a>
                        @endif
                    <?php endforeach?>
                </div>
            <?php elseif(count($images) == 3):?>
                <div class="three-images">
                    <div class="top"><a class="preview-image" rel="{{$post->id}}-images" href="{{Image::url($images[0], '960')}}" style="background-image: url({{Image::url($images[0], 600)}})"></a> </div>
                    <div class="bottom">
                        <?php array_shift($images); foreach($images as $image):?>
                            @if(!empty($image))
                            <a class="preview-image" href="{{Image::url($image, '960')}}" rel="{{$post->id}}-images" style="background-image:url({{Image::url($image, 600)}})"></a>
                            @endif
                        <?php endforeach?>
                    </div>
                </div>
            <?php else:?>
                <div class="four-images">
                    <div class="top"><a class="preview-image" rel="{{$post->id}}-images" href="{{Image::url($images[0], '960')}}" style="background-image: url({{Image::url($images[0], 600)}})"></a> </div>
                    <div class="bottom clearfix">
                        <?php $i = 1;array_shift($images); foreach($images as $image):?>
                            <?php if($i <= 3):$i++?>
                                <a class="preview-image" href="{{Image::url($image, '960')}}" rel="{{$post->id}}-images" style="background-image:url({{Image::url($image, 600)}})">
                                    <?php if($i == 4 and (count($images) + 1) > 4):?>
                                        <div class="more-photo-count"><span>+<?php echo count($oImages) - 4?></span></div>
                                    <?php endif?>
                                </a>
                            <?php else: break; endif?>
                        <?php endforeach?>
                        <?php $i = 0;foreach($oImages as $image):?>
                            <?php if($i > 3):?>
                                <a style="display: none" class="preview-image" href="{{Image::url($image, '960')}}" rel="{{$post->id}}-images" style="background-image:url({{Image::url($image, 600)}})">
                                </a>
                            <?php endif;$i++?>
                        <?php endforeach?>
                    </div>
                </div>
            <?php endif?>

        </div>
        @elseif($post->content_type == 'video' and $videoUrl = $post->present()->getVideoUrl())
        <div class="load-video" data-url="{{$videoUrl}}"></div>

        @elseif($post->content_type == 'audio' and $audioLink = $post->present()->getSoundCloudUrl())

        <div class="load-sound" data-url="{{$audioLink}}"></div>

        @elseif($post->content_type == 'location' and $location = $post->present()->getLocation())


        <img class="post-location-image" src="https://maps.googleapis.com/maps/api/staticmap?center={{$location}}&zoom=15&size=700x300&maptype=roadmap&markers=color:red%7C{{$location}}&sensor=false&scale=1&visual_refresh=true"/>


        @elseif($post->content_type == 'movie' and $movie = $post->present()->generalMediaValue())

            @if(!empty($movie))
            <div class="general-media">

                <span><i class="icon ion-coffee"></i> {{trans('post.i-watch')}}:</span> {{sanitizeText(str_limit($movie, 60, ''))}}
            </div>
            @endif
        @elseif($post->content_type == 'oembed' and $oembed = $post->present()->getOEmbed())
            <div class="oembed-iframe">{{$oembed}}</div>
        @endif

        @if(!empty($post->video_path))
            <iframe allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" style="border: none;padding: 0;border-radius: 10px;overflow: hidden" src="{{URL::route('play-video')}}?path={{$post->video_path}}" width="100%" height="330"></iframe>
        @endif


        @if($post->present()->hasLink())
        {{Theme::section('post.link', ['post' => $post])}}
        @endif

        @if($post->file_path)
            <div class="post-file-display media">
                <div class="media-object pull-left">
                    <?php $ext = pathinfo($post->file_path, PATHINFO_EXTENSION)?>
                    <img src="{{Theme::asset()->img('theme/images/file-icons/'.$ext.'.png')}}"/>
                </div>

                <div class="media-body">
                    <h4>{{$post->file_path_name}}</h4>
                    <a href="{{URL::route('post-download-file', ['id' => $post->id])}}" class="btn btn-primary">{{trans('post.download-file')}}</a>
                </div>
            </div>
        @endif
        {{Theme::extend('post-body', [$post])}}

        <?php $tags = $post->present()->listTags()?>
        @if(count($tags) > 0)
        <div class="post-tags">


            <span>With : </span>
            <?php $counter = 0;?>
            @foreach($tags as $taggedUser)

            @if($counter <3)
            <?php $counter +=1?>
            <a data-ajaxify="true" href="{{$taggedUser->present()->url()}}">{{$taggedUser->present()->fullName()}}</a>,
            @endif


            @endforeach

            @if(count($tags) > 3)
                <span class="dropdown">
                    <a href="" class="dropdown-toggle" data-toggle="dropdown"><i class="icon ion-more"></i> {{trans('global.more')}}</a>
                    <ul class="dropdown-menu">
                        <?php $counter = 0?>
                        @foreach($tags as $taggedUser)
                        <?php $counter +=1?>
                        @if($counter > 3)

                        <li><a data-ajaxify="true" href="{{$taggedUser->present()->url()}}">{{$taggedUser->present()->fullName()}}</a></li>
                        @endif
                        @endforeach
                    </ul>
                </span>
            @endif
        </div>
        @endif

    </div>

    <div class="post-footer">
        <ul class="nav nav-left">
            @if($post->content_type != 'auto-post')
            <li><a href="" data-is-login="{{Auth::check()}}" class="post-share-button" data-id="{{$post->id}}"><i class="icon ion-reply"></i> <span>{{trans('post.share')}}</span></a> </li>
            @endif
            <li>
                <?php $hasLike = $post->hasLiked()?>

                <a data-is-login="{{Auth::check()}}" data-status="{{($hasLike) ? '1' : 0}}" class="like-button" data-like="{{trans('like.like')}}" data-unlike="{{trans('like.unlike')}}" data-id="{{$post->id}}" data-type="post" href=""><i class="icon ion-ios7-heart"></i> <span>{{($hasLike) ? trans('like.unlike') : trans('like.like')}}</span></a>
            </li>
            @if (Auth::check())
            <li>
                <?php $nStatus = $post->present()->canReceiveNotification()?>

                <a data-userid="{{Auth::user()->id}}" data-on="{{trans('notification.on-notifications')}}" data-status="{{$nStatus}}" data-off="{{trans('notification.off-notifications')}}" class="toggle-notification-receiver" data-type="post" data-type-id="{{$post->id}}" href="">
                    <i class="icon ion-ios7-bell-outline"></i>

                        <span>
                            @if ($nStatus)
                                {{trans('notification.off-notifications')}}
                            @else
                                {{trans('notification.on-notifications')}}
                            @endif
                        </span>
                </a>
            </li>
            @endif
        </ul>

        <ul class="nav nav-right pull-right">
            @if($post->content_type != 'auto-post')
            <li><a data-id="{{$post->id}}" data-loading="{{trans('post.loading')}}" class="post-activity-loader" href="{{URL::route('load-shares', ['id' => $post->id])}}"><i class="icon ion-reply"></i> <span class="post-share-count-{{$post->id}}">{{$post->shared_count}}</span></a> </li>
            @endif
            <li><a data-id="{{$post->id}}" data-loading="{{trans('post.loading')}}" class="post-activity-loader" href="{{URL::route('show-likes', ['post' => 'post', 'id' => $post->id])}}"><i class="icon ion-ios7-heart"></i> <span class="post-like-count-{{$post->id}}">{{$post->countLikes()}}</span></a> </li>
            <li><a  href="javascript:void(0)"><i class="icon ion-reply-all"></i> <span class="post-reply-count-{{$post->id}}">{{$post->countComments()}}</span></a> </li>
        </ul>


    </div>
    <div id="post-activity-{{$post->id}}" class="post-activity">
        {{trans('post.loading')}}
    </div>
    <div class="post-replies" id="{{$post->id}}-post-replies" data-limit="3" data-offset="3" data-type="post" data-type-id="{{$post->id}}">

        @if ($post->countComments() > 3)
        <a style="" href="" class="load-more-comment" data-target="#{{$post->id}}-post-replies">
            <i class="icon ion-chatbox"></i> {{trans('comment.view-more-comment')}} <img class="indicator" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/>
        </a>
        @endif
        <div id="post-{{$post->id}}-reply-lists" class="replies-list">

            @foreach($post->comments->take(3)->reverse() as $comment)

            @if ($post->type == 'page' and $post->page->user->id == $comment->user->id)
            {{Theme::section('comment.display-page', ['comment' => $comment, 'page' => $post->page])}}
            @else
            {{Theme::section('comment.display', ['comment' => $comment])}}
            @endif

            @endforeach
        </div>
        @if(Auth::check())
        @if($post->type == 'page' and ($post->page->present()->isAdmin() or $post->page->present()->isEditor() or $post->page->present()->isModerator()))
            {{Theme::section('comment.form', ['avatar' => $post->page->present()->getAvatar(100),'typeId' => $post->id, 'type' => 'post'])}}
        @elseif($post->type == 'community' and $post->community->present()->isMember())
            {{Theme::section('comment.form', ['typeId' => $post->id, 'type' => 'post'])}}
        @else
            {{Theme::section('comment.form', ['typeId' => $post->id, 'type' => 'post'])}}
        @endif
        @endif

    </div>
</div>
@else

    <?php $post->delete()?>
@endif