<div class="post-header clearfix">
            <div class="header-object">
                <a class="user-popover" data-url="{{$post->user->present()->popoverUrl()}}" href="{{$post->user->present()->url()}}"><img src="{{$post->user->present()->getAvatar(100)}}"/></a>
            </div>
            <div class="header-body">
                <h3 class="title">

                    <a data-ajaxify="true" href="{{$post->user->present()->url()}}">{{$post->user->fullname}}</a> <span>{{$post->user->present()->atName()}} {{($post->shared) ? 'shared a post' : null}}</span>
                    {{Theme::section('user.verified', ['user' => $post->user])}}

                        @if(Request::segment(1) != 'community' and $post->community)
                            <i class="icon ion-ios7-arrow-thin-right"></i> <a data-ajaxify="true" href="{{$post->community->present()->url()}}">{{$post->community->title}}</a>
                        @endif

                </h3>
                <span class="post-time">
                     <i class="icon ion-ios7-time-outline"></i> <span title="{{$post->present()->time()}}">{{formatDTime($post->created_at)}}</span>
                        <span class="post-is-edited-{{$post->id}}">
                            @if($post->edited)
                            {{trans('post.edited')}}
                            @endif
                        </span>
                     @if(!empty($post->type_id) and Request::segment(3) != 'category' and $post->community and  $post->communityCategory)
                        <span style="font-weight: bold">
                            <i class="icon ion-ios7-arrow-thin-right"></i>
                            <a data-ajaxify="true" href="{{$post->community->present()->url('category/'.$post->communityCategory->slug)}}">{{$post->communityCategory->title}}</a>
                        </span>
                     @endif
                </span>

                {{Theme::extend('post-header', ['post' => $post])}}

                <div class="post-action-dropdown dropdown">
                    <a  data-hover="dropdown" data-toggle="dropdown" class="dropdown-toggle" href=""><i class="icon ion-ios7-arrow-down"></i></a>



                         <ul class="dropdown-menu pull-right">
                             @if (Auth::check())
                             @if (Auth::user()->id != $post->user->id)
                             <li><a data-id="{{$post->id}}" href="" class="hide-post">{{trans('post.dont-want-see')}}</a> </li>
                             @endif

                            @if (Auth::user()->id != $post->user->id)
                                <li><a data-location="post" data-userid="{{$post->user->id}}" class="block-user" href="">{{trans('user.block-user')}}</a> </li>
                            @endif

                            <li><a href="{{URL::route('report', ['type' => 'post'])}}?url={{$post->present()->url()}}">{{trans('post.report-post')}}</a> </li>

                            @if ($post->present()->canDelete() or $post->community->present()->canManage())
                                <li><a href="" data-id="{{$post->id}}" class="edit-post-trigger">{{trans('post.edit')}}</a> </li>
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