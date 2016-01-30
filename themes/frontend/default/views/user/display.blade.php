<div class=" user media {{(isset($mini)) ? 'user-mini' : null}} {{(isset($miniActions)) ? 'user-mini-actions' : null}}">
      <div class="media-object pull-left">
           <a data-url="{{$user->present()->popoverUrl()}}" class="user-popover" href="{{$user->present()->url()}}" data-ajaxify="true"><img src="{{$user->present()->getAvatar(100)}}"/></a>
      </div>
      <div class="media-body">
          <a  data-ajaxify="true" href="{{$user->present()->url()}}">
              <h5 class="media-heading">{{$user->fullname}} {{Theme::section('user.verified', ['user' => $user])}} <span>{{$user->present()->atName()}}</span> </h5>
          </a>
          {{Theme::extend('user-display', ['user' => $user])}}

          @if(!isset($actions))
                <div class="action-buttons">
                   {{Theme::section('connection.buttons', ['user' => $user])}}
               </div>
           @endif
      </div>
</div>