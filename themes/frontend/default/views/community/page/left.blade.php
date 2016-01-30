<div class="box">
    <div class="box-title" style="margin-bottom: 0">
        {{$community->title}}
        @if($community->present()->isMember())
                    <?php $nStatus = $community->present()->canReceiveNotification()?>

                    <a data-userid="{{Auth::user()->id}}" data-on="On Notifications" data-status="{{$nStatus}}" data-off="Off Notifications" class="toggle-notification-receiver" data-type="community" data-type-id="{{$community->id}}" href="">
                        <i class="icon ion-ios7-bell-outline"></i>

                        <span>
                            @if ($nStatus)
                                {{trans('notification.off-notifications')}}
                            @else
                                {{trans('notification.on-notifications')}}
                            @endif
                        </span>
                    </a>

        @endif
        <span class="pull-right dropdown">
            <a class="dropdown-toggle" data-hover="dropdown" data-toggle="dropdown" href=""><i class="icon ion-chevron-down"></i></a>
            <ul class="dropdown-menu">
                @if(!$community->isOwner() and $community->present()->isMember())
                    <li><a href="{{URL::route('leave-community', ['id' => $community->id])}}">{{trans('community.leave')}}</a> </li>
                @endif

                @if($community->isOwner())
                    <li> <a data-ajaxify="true" href="{{$community->present()->url('edit')}}" >{{trans('community.edit')}}</a></li>
                @else
                    <li><a href="{{URL::route('report', ['type' => 'community'])}}?url={{$community->present()->url()}}"><i class="icon ion-flag"></i> {{trans('community.report')}}</a> </li>
                @endif


            </ul>
        </span>
    </div>
    <div  class="community-cover" style="background: url({{$community->present()->getLogo()}})">
        <div data-id="{{$community->id}}" style="background: url({{$community->present()->getLogo()}})" id="croppic-community-cover"></div>
        <input type="hidden" id="community-cropped-cover-image"/>
        @if($community->present()->isAdmin())
            <a id="change-community-cover" href="javascript:void(0)" class="btn btn-success btn-xs" style="margin: 10px 10px">{{trans('community.change-cover')}}</a>

            <a href="{{$community->present()->url('edit')}}" class="btn btn-danger btn-xs pull-right" style="margin: 10px 10px">{{trans('global.edit')}}</a>
        @endif



        <div class="cover-footer">
            <i class="icon ion-locked"></i> {{$community->present()->getPrivacy()}}
             <span class="pull-right">{{$community->countMembers()}} {{trans('community.members')}}</span>
        </div>
    </div>

    <div class="nav-box">
        <ul class="nav">
            <li><a data-ajaxify="true" href="{{$community->present()->url()}}">{{trans('community.all-post')}}</a> </li>

            @foreach($community->categories as $category)
                <li class="category-{{$category->id}}">

                    <a data-ajaxify="true" href="{{$community->present()->url('category/'.$category->slug)}}"><i class="icon ion-ios7-arrow-thin-right"></i> {{$category->title}}</a>
                    @if($community->isOwner())
                        <a href="javascript:void(0)" class="community-category-remove" data-id="{{$category->id}}" style="display: inline;position:absolute;top: 0;right: 0"><i class="icon ion-android-close"></i></a>
                    @endif
                 </li>
            @endforeach

            @if($community->isOwner())
                <li>
                    <a  href="" class="community-create-category-button"><i class="icon ion-plus"></i> {{trans('community.add-category')}}</a>
                    <form data-id="{{$community->id}}" class="community-create-category-form" action="" method="post">
                        <input class="form-control input-sm" type="text" placeholder="Category name"/>
                        <button class="btn btn-success btn-xs"><i class="icon ion-plus"></i> {{trans('global.add')}}</button>
                    </form>

                 </li>
            @endif

            <li><a data-ajaxify="true" href="{{$community->present()->url('about')}}">{{trans('community.about')}}</a> </li>
            @if($community->present()->canInvite())
                <li><a data-ajaxify="true" href="{{$community->present()->url('invite')}}">{{trans('community.invite-members')}}</a> </li>
            @endif
        </ul>
    </div>
</div>

<div class="box">
    <div class="box-title">{{trans('community.created-by')}}</div>
    <div class="box-content">
        {{Theme::section('user.display', ['user' => $community->user, 'mini' => true, 'miniActions' => true])}}
    </div>
</div>

<div class="box">
    <div class="box-title">{{trans('community.members')}} ({{$community->countMembers()}}) <a class="pull-right" href="{{$community->present()->url('members')}}"><i class="icon ion-more"></i> {{trans('global.more')}}</a> </div>
    <div class="box-content">
            <div class="user-tile-list">
                <a data-ajaxify="true" href="{{$community->user->present()->url()}}"><img src="{{$community->user->present()->getAvatar(100)}}"/> </a>
                <?php $count = 0;?>
                @foreach($community->members->take(6) as $member)

                        <a data-ajaxify="true" href="{{$member->user->present()->url()}}"><img src="{{$member->user->present()->getAvatar(100)}}"/> </a>

                @endforeach

            </div>
    </div>
</div>
