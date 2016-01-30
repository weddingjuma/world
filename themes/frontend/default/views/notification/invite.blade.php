<div class="user media user-mini notification {{$notification->id}}-notification">
      <div class="media-object pull-left">
            <a href="{{$notification->user->present()->url()}}" data-ajaxify="true"><img src="{{$notification->user->present()->getAvatar(100)}}"/></a>
      </div>
      <div class="media-body">

           @if ($type == 'community')
                <?php $community = app('App\\Repositories\\CommunityRepository')->get($typeId)?>
                @if ($community)
                    <h5 class="media-heading"><i class="icon ion-speakerphone"></i>  {{$notification->user->fullname}} {{trans('notification.invited-you-to')}}
                        <a href="{{$community->present()->url()}}" data-ajaxify="true" >{{$community->title}}</a> {{trans('community.community')}}
                     </h5>
                @endif

           @elseif($type == 'page')

              <?php $page = app('App\\Repositories\\PageRepository')->get($typeId)?>
              @if ($page)
                  <h5 class="media-heading"><i class="icon ion-speakerphone"></i>  {{$notification->user->fullname}} {{trans('notification.invited-you-to-like')}}
                      <a href="{{$page->present()->url()}}" data-ajaxify="true" >{{$page->title}}</a>
                  </h5>
              @else

              @endif
           @endif

          <span class="post-time"> <i class="icon ion-ios7-time-outline"></i> <span title="{{$notification->present()->time()}}">{{$notification->created_at}}</span></span>
           <div class="action-buttons">
                <a data-id="{{$notification->id}}" class="delete-button" href=""><i class="icon ion-close"></i></a>
           </div>
      </div>

</div>