<div class="container page-content clearfix">
    <div id="profile-header" data-id="{{$page->id}}" class="profile-header" style=" background: none" >
        <div class="container">
            <div class="profile-info">
                <div class="avatar">
                    @if(Auth::check() and ($page->isOwner() or $page->present()->isAdmin() or $page->present()->isEditor()))
                    <a class="change-photo-button"  data-ajaxify="true"  href="{{$page->present()->url('edit')}}"><i class="icon ion-android-camera"></i></a>
                    @endif

                    <a class="preview-image" rel="profile-images" href="{{$page->present()->getAvatar(600)}}"><img src="{{$page->present()->getAvatar(600)}}"/></a>
                </div>
                <h4 class="title">{{$page->title}} {{Theme::section('page.verified', ['page' => $page])}}</h4>

                <span class="about-info">{{$page->description}}</span>
            </div>


        </div>
        <div class="profile-cover-indicator">
            <div>
                <img src="{{Theme::asset()->img('theme/images/loading.gif')}}"/>
                <span class="upload-status">0%</span>
            </div>
        </div>
        <div class="profile-resize-cover">
            <img src="{{$page->present()->getCover()}}"/>
        </div>
        <div class="original-profile-cover">
            <img src="{{$page->present()->getOriginalCover()}}"/>
            <input type="hidden" name="" id="profile-cover-resized-top">
        </div>
        <div class="profile-cover-reposition-button">
            <button onclick="return cancelReposition()" class="btn btn-sm btn-default">{{trans('global.cancel')}}</button>
            <button onclick="return saveProfileCover('page/crop/cover/{{$page->id}}')" class="btn btn-sm btn-success">{{trans('global.save')}}</button>
        </div>
        <div class="profile-cover-changer">
            <div class="dropdown">

                @if(Auth::check() and $page->isOwner())
                <button class="btn btn-info btn-sm dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <i class="ion ion-android-image"></i> {{trans('user.change-cover')}}
                </button>
                @endif
                <form id="profile-cover-form" action="" method="post" enctype="multipart/form-data">
                    <span class="file-input"><input data-id="{{$page->id}}" class="page-profile-cover-chooser" id="profile-cover-chooser" type="file" name="image"/></span>
                </form>
                <ul class="dropdown-menu pull-right" aria-labelledby="dropdownMenu1">
                    <li>

                        <a onclick="return file_chooser('#profile-cover-chooser')" href="#">{{trans('user.upload-a-photo')}} </a></li>
                    <li><a onclick="return reposition_cover()" href="#">{{trans('user.reposition')}}</a></li>
                    <li><a onclick="return remove_page_cover('{{$page->id}}','{{Theme::asset()->img('theme/images/profile-cover.jpg')}}', '{{trans('global.confirm-delete')}}')" href="#">{{trans('global.remove')}}</a></li>
                </ul>
            </div>
        </div>

        <div class="profile-nav">
            <div class="container">
                <ul class="nav">
                    <li class="{{(Request::segment(3) == '') ? 'active' : null}}"><a data-ajaxify="true" href="{{$page->present()->url()}}">{{trans('global.timeline')}}</a> </li>

                    <li class="{{(Request::segment(3) == 'photos') ? 'active' : null}}"><a data-ajaxify="true" href="{{$page->present()->url('photos')}}">{{trans('photo.photos')}}</a> </li>
                    @if(Auth::check() and $page->isOwner())
                    <li class="{{(Request::segment(3) == 'design') ? 'active' : null}}"><a data-ajaxify="true" href="{{$page->present()->url('design')}}">{{trans('global.design')}}</a> </li>
                    @endif
                    <!--<li class="{{(Request::segment(3) == 'followers') ? 'active' : null}}"><a data-ajaxify="true" href="{{$page->present()->url('connection/followers')}}">Photos</a> </li>-->

                </ul>

                <div class="profile-nav-right ">


                        <?php $hasLike = $page->hasLiked()?>

                        <a  data-is-login="{{Auth::check()}}" data-status="{{($hasLike) ? '1' : 0}}" class="btn btn-default btn-sm like-button" data-like="{{trans('like.like')}}" data-unlike="{{trans('like.unlike')}}" data-id="{{$page->id}}" data-type="page" href=""><i class="icon ion-ios7-heart"></i> <span>{{($hasLike) ? trans('like.unlike') : trans('like.like')}}</span></a>

                        @if(Auth::check())
                            <span class="dropdown">
                                    <a data-toggle="dropdown" href="" class="btn btn-sm btn-success dropdown-toggle"><i class="icon ion-arrow-down-b"></i></a>
                                    <ul class="dropdown-menu pull-right">

                                        @if (Auth::check() and !$page->isOwner())
                                        <li><a href="{{URL::route('report', ['type' => 'page'])}}?url={{$page->present()->url()}}">{{trans('page.report-page')}}</a> </li>

                                        @endif
                                        <li class="dropdown-divider"></li>
                                        @if(Auth::check() and ($page->isOwner() or $page->present()->isAdmin() or $page->present()->isEditor()))

                                        <li><a data-ajaxify="trues" href="{{$page->present()->url('edit')}}">{{trans('page.edit-page')}}</a> </li>
                                        @endif

                                        @if(Auth::check() and ($page->isOwner() or $page->present()->isAdmin()))

                                        <li><a data-ajaxify="trues" href="{{$page->present()->url('roles')}}">{{trans('page.page-roles')}}</a> </li>
                                        @endif

                                        @if(Auth::check() and $page->isOwner())
                                            <li><a class="page-delete-button" data-ajaxify="trues" href="{{URL::route('pages-delete', ['id' => $page->id])}}">{{trans('page.delete-page')}}</a> </li>
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
        {{Theme::section('page.profile.side')}}
        {{Theme::section('page.profile.side-likes')}}
        {{Theme::widget()->get('page-profile')}}
    </div>
    @endif
</div>