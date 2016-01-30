<div class="user media user-mini notification {{$notification->id}}-notification">
    <div class="media-object pull-left">
        <a href="{{$notification->user->present()->url()}}" data-ajaxify="true"><img src="{{$notification->user->present()->getAvatar(100)}}"/></a>
    </div>
    <div class="media-body">
        <h5 class="media-heading">
            <i class="icon ion-speakerphone"></i>
            <a href="{{$notification->user->present()->url()}}">
                {{$notification->user->fullname}}
            </a>


            <?php
                switch($admin->type) {
                    case 1:
                        $trans = 'page.make-you-admin';
                        break;
                    case 2 :
                        $trans = "page.make-you-editor";
                        break;
                    default :
                        $trans = "page.make-you-moderator";
                        break;
                }
            ?>
            @if($admin->page)

                {{trans($trans)}}

                <a data-ajaxify="true" href="{{$admin->page->present()->url()}}">{{$admin->page->title}}</a>
            @else
                "{{trans('notification.not-available')}}"
            @endif

        </h5>

        <span>{{trans($notification->content)}}</span>
        <span class="post-time"> <i class="icon ion-ios7-time-outline"></i> <span title="{{$notification->present()->time()}}">{{$notification->created_at}}</span></span>
        <div class="action-buttons">
            <a data-id="{{$notification->id}}" class="delete-button" href=""><i class="icon ion-close"></i></a>
        </div>
    </div>

</div>