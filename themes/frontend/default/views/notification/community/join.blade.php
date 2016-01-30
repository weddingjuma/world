<div class="user media user-mini notification {{$notification->id}}-notification">
      <div class="media-object pull-left">
            <a href="{{$notification->user->present()->url()}}" data-ajaxify="true"><img src="{{$notification->user->present()->getAvatar(100)}}"/></a>
      </div>
      <div class="media-body">
           <h5 class="media-heading">
               <i class="icon ion-speakerphone"></i>
               <a href="{{$notification->user->present()->url()}}">{{$notification->user->fullname}}</a> {{trans('notification.joined')}}  "<a data-ajaxify="true" href="{{$community->present()->url()}}">{{$community->title}}</a>"
           </h5>

          <span>{{trans($notification->content)}}</span>
          <span class="post-time"> <i class="icon ion-ios7-time-outline"></i> <span title="{{$notification->present()->time()}}">{{$notification->created_at}}</span></span>
           <div class="action-buttons">
                <a data-id="{{$notification->id}}" class="delete-button" href=""><i class="icon ion-close"></i></a>
           </div>
      </div>

</div>