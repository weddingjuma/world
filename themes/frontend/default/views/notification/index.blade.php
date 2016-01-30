<div class="container page-content clearfix">

        <div class="left-column">

            <div class="box">
                <div class="box-title" style="height: 50px">
                    {{trans('notification.notifications')}}

                    <span class="pull-right btn-group">
                        <a href="{{URL::route('notification-clear-all')}}" class="btn btn-default btn-sm">{{trans('notification.clear-all')}}</a>
                        <a class="btn btn-default btn-sm" href="{{URL::route('notification-mark-all')}}">{{trans('notification.mark-all')}}</a>
                    </span>
                </div>
                <div class="box-content">

                    @foreach($notifications as $notification)
                        {{$notification->present()->render()}}
                    @endforeach

                    {{$notifications->links()}}
                </div>
            </div>

        </div>

        <div class="right-column">
            {{Theme::section('user.side-info')}}
            {{Theme::widget()->get('notifications')}}

        </div>
    </div>