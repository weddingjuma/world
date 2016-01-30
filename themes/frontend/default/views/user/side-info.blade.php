<div id="preview-card" class="box box-preview-card">

    <div class="cover">
        <a href="{{$loggedInUser->present()->url()}}" data-ajaxify="true">
            <img  src="{{(!empty($loggedInUser->cover)) ? $loggedInUser->present()->coverImage() : Theme::asset()->img('theme/images/profile-cover.jpg')}}"/>
        </a>
    </div>
    <div  class="media">
                    <div class="media-object pull-left">
                        <a href="{{$loggedInUser->present()->url()}}" data-ajaxify="true">
                            <img src="{{$loggedInUser->present()->getAvatar(150)}}"/>
                        </a>
                    </div>
                    <div class="media-body">

                        <h4 class="media-heading">{{$loggedInUser->fullname}}</h4>
                        <span>@ {{$loggedInUser->username}}</span>
                    </div>
                </div>


</div>
{{Theme::section('user.action-buttons')}}
{{Theme::extend('user-side-preview-card')}}