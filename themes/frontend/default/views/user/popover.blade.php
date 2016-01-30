<div id="preview-card" class="user-popover">
    <div class="cover">
        <img class="" src="{{(!empty($user->cover)) ? $user->present()->coverImage() : Theme::asset()->img('theme/images/profile-cover.jpg')}}"/>
    </div>


    <div  class="media">
                    <div class="media-object pull-left">
                       <a data-ajaxify="true" href="{{$user->present()->url()}}"> <img src="{{$user->present()->getAvatar(150)}}"/></a>
                    </div>
                    <div class="media-body">

                        <a data-ajaxify="true" href="{{$user->present()->url()}}"><h4 class="media-heading">{{$user->fullname}}</h4></a>
                        <span>@ {{$user->username}}</span> {{Theme::section('user.verified', ['user' => $user])}}
                    </div>
                </div>


</div>
<div class="user-bio">{{$user->bio}}</div>
@if(Auth::check())
<div class="popover-action-buttons">
    {{Theme::section('connection.buttons', ['user' => $user])}}
</div>
@endif