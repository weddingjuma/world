<div class="container page-content clearfix">
    <div id="profile-header" class="profile-header" style="background: none" >
        <div class="container">
            <div class="profile-info">
                <div class="avatar">

                    <form id="account-form" method="post" enctype="multipart/form-data">
                        @if(Auth::check() and $profileUser->isOwner())
                        <span style=""  class="change-photo-button fileupload fileupload-exists" data-provides="fileupload">

                                                     <a style="color: #ffffff;border-radius:10px"   class=" btn-file">
                                                         <span class="fileupload-new"><i class="icon ion-android-camera"></i></span>
                                                         <span class="fileupload-exists"><i class="icon ion-android-camera"></i></span>
                                                         <input title="" id="image-input" class="" type="file" name="image">
                                                     </a>


                                                 </span>

                        @endif
                        <a class="preview-image media-object" rel="profile-images" href="{{$profileUser->present()->getAvatar(600)}}"><img src="{{$profileUser->present()->getAvatar(600)}}"/></a>
                    </form>

                </div>
                <h4 class="title">{{$profileUser->fullname}} {{Theme::section('user.verified', ['user' => $profileUser])}}</h4>

                <span class="about-info">{{str_limit($profileUser->bio, 50)}}</span>
            </div>


        </div>
        <div class="profile-cover-indicator">
            <div>
                <img src="{{Theme::asset()->img('theme/images/loading.gif')}}"/>
                <span class="upload-status">0%</span>
            </div>
        </div>
        <div class="profile-resize-cover">
            <img src="{{$profileUser->present()->getCover()}}"/>
        </div>
        <div class="original-profile-cover">
            <img src="{{$profileUser->present()->getOriginalCover()}}"/>
            <input type="hidden" name="" id="profile-cover-resized-top">
        </div>
        <div class="profile-cover-reposition-button">
            <button onclick="return cancelReposition()" class="btn btn-sm btn-default">{{trans('global.cancel')}}</button>
            <button onclick="return saveProfileCover('profile/crop/cover')" class="btn btn-sm btn-success">{{trans('global.save')}}</button>

        </div>
        <div class="profile-cover-changer">
            <div class="dropdown">

                @if(Auth::check() and $profileUser->id == Auth::user()->id)
                    <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="ion ion-android-image"></i> {{trans('user.change-cover')}}
                    </button>
                @endif
                <form id="profile-cover-form" action="" method="post" enctype="multipart/form-data">
                    <span class="file-input"><input class="user-profile-cover-chooser" id="profile-cover-chooser" type="file" name="image"/></span>
                </form>
                <ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu1">
                    <li>

                        <a onclick="return file_chooser('#profile-cover-chooser')" href="#">{{trans('user.upload-a-photo')}} </a></li>
                    <li><a onclick="return reposition_cover()" href="#">{{trans('user.reposition')}}</a></li>
                    <li><a onclick="return remove_user_cover('{{Theme::asset()->img('theme/images/profile-cover.jpg')}}', '{{trans('global.confirm-delete')}}')" href="#">{{trans('global.remove')}}</a></li>
                </ul>
            </div>
        </div>

        <div class="profile-nav">
            <div class="container">
                <ul class="nav">
                    <li class="{{(Request::segment(2) == '') ? 'active' : null}}"><a data-ajaxify="true" href="{{$profileUser->present()->url()}}">{{trans('global.timeline')}}</a> </li>
                    <li class="{{(Request::segment(3) == 'friends') ? 'active' : null}}"><a data-ajaxify="true" href="{{$profileUser->present()->url('connection/friends')}}"> {{$profileUser->countFriends()}} {{trans('connection.friends')}}</a> </li>
                    <li class="{{(Request::segment(3) == 'followers') ? 'active' : null}}"><a data-ajaxify="true" href="{{$profileUser->present()->url('connection/followers')}}">{{$profileUser->countFollowers()}} {{trans('connection.followers')}}</a> </li>

                    <li class="{{(Request::segment(2) == 'photos' or Request::segment(2) == 'album') ? 'active' : null}}"><a data-ajaxify="true" href="{{$profileUser->present()->url('photos')}}">{{$profileUser->countPhotos()}} {{trans('photo.photos')}}</a> </li>

                    {{Theme::extend('user-profile-menu')}}
                </ul>

                <div class="profile-nav-right">
                    @if($profileUser->present()->canSendMessage())
                        <a href="" data-userid="{{$profileUser->id}}" data-label="{{trans('message.send-message')}}" class="btn btn-success btn-xs send-message-button">{{trans('message.send-message')}}</a>
                    @endif
                    {{Theme::section('connection.buttons', ['user' => $profileUser])}}

                        @if(Auth::check())
                            <span class="dropdown">
                            <a data-toggle="dropdown" href="" class="btn btn-sm btn-success dropdown-toggle"><i class="icon ion-arrow-down-b"></i></a>
                            <ul class="dropdown-menu pull-right">

                                @if (Auth::check() and Auth::user()->id != $profileUser->id)
                                <li><a href="{{URL::route('report', ['type' => 'profile'])}}?url={{$profileUser->present()->url()}}">{{trans('user.report-profile')}}</a> </li>
                                <li><a data-location="profile"  data-userid="{{$profileUser->id}}" class="block-user" href="">{{trans('user.block-user')}}</a> </li>
                                @endif
                                <li class="dropdown-divider"></li>
                                @if(Auth::check() and $profileUser->id == Auth::user()->id)
                                <li><a data-ajaxify="trues" href="{{URL::route('edit-profile')}}">{{trans('user.edit-profile')}}</a> </li>

                                @endif

                            </ul>
                        </span>
                        @endif
                </div>
            </div>
        </div>
    </div>
    @if (isset($error) or isset($singleColumn))
    {{$content}}
    @else
    <div class="left-column">
        {{$content}}

    </div>

    <div class="right-column">
        {{Theme::section('profile.side-content')}}
        {{Theme::section('connection.side-lists', ['user' => $profileUser])}}
        {{Theme::section('photo.side', ['user' => $profileUser])}}
        {{Theme::widget()->get('user-profile')}}
    </div>
    @endif
</div>