<div class="post-header clearfix">
            <div class="header-object">
                <a class="user-popover" data-url="{{$post->user->present()->popoverUrl()}}" href="{{$post->user->present()->url()}}"><img src="{{$post->user->present()->getAvatar(100)}}"/></a>
            </div>
            <div class="header-body">
                <h3 class="title">

                    <a  data-ajaxify="true" href="{{$post->user->present()->url()}}">{{$post->user->fullname}}</a> <span>{{$post->user->present()->atName()}} {{($post->shared) ? 'shared a post' : null}}</span>
                    {{Theme::section('user.verified', ['user' => $post->user])}}
                    @if(!empty($post->to_user_id) and $post->toUser)
                        <i class="icon ion-ios7-arrow-thin-right"></i> <a data-ajaxify="true" href="{{$post->toUser->present()->url()}}">{{$post->toUser->fullname}}</a>
                    @endif

                    @if($post->present()->isAutoPost())
                        {{Theme::section('post.auto-post.header', ['post' => $post])}}
                    @endif
                </h3>
                <span class="post-time"><i class="icon ion-ios7-time-outline"></i>

                    <span title="{{$post->present()->time()}}">{{formatDTime($post->created_at)}}</span> <span style="font-weight: bold"><i class="icon ion-ios7-arrow-thin-right"></i>
                        <span class="post-is-edited-{{$post->id}}">
                            @if($post->edited)
                            {{trans('post.edited')}}
                            @endif
                        </span>
                        {{$post->present()->getPrivacy()}}</span></span>

                {{Theme::extend('post-header', ['post' => $post])}}

                <div class="post-action-dropdown dropdown">
                    <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" href=""><i class="icon ion-ios7-arrow-down"></i></a>


                         <ul class="dropdown-menu pull-right">
                             @if (Auth::check())

                             @if (Auth::user()->id != $post->user->id)
                                <li><a data-id="{{$post->id}}" href="" class="hide-post">{{trans('post.dont-want-see')}}</a> </li>
                             @endif
                            @if (Auth::user()->id != $post->user->id)
                                <li><a data-location="post" data-userid="{{$post->user->id}}" class="block-user" href="">{{trans('user.block-user')}}</a> </li>
                            @endif

                            <li><a href="{{URL::route('report', ['type' => 'post'])}}?url={{$post->present()->url()}}">{{trans('post.report-post')}}</a> </li>


                            @if ($post->present()->canDelete())
                                @if(!$post->shared)
                                    <li><a href="" data-id="{{$post->id}}" class="edit-post-trigger">{{trans('post.edit')}}</a> </li>
                                @endif
                                <li><a data-id="{{$post->id}}" class="delete-post" data-message="Do you really want to delete this post" href="">{{trans('post.remove')}}</a> </li>
                            @endif

                             {{Theme::extend('post-header-links', ['post' => $post])}}

                             @endif
                             <li><a href="{{route('post-page', ['id' => $post->id])}}">{{trans('post.view-post')}}</a> </li>
                             {{Theme::section('post.social-share', ['post' => $post])}}
                         </ul>

                </div>
            </div>

    </div>