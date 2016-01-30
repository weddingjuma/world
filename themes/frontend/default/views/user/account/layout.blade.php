<div  class="container page-content clearfix">
    <div id="account-container" class="left-column">
        {{$content}}
    </div>
    <div class="right-column">
        {{Theme::section('user.side-info')}}
        <div class="box nav-box">
                <div class="box-title">{{trans('user.account-menu')}}</div>
                <ul class="nav">
                    <li><a data-ajaxify="true" href="{{URL::route('user-account')}}">{{trans('user.account')}}</a> </li>
                    <li><a data-ajaxify="true" href="{{URL::route('user-account-privacy')}}">{{trans('user.security-privacy')}}</a> </li>
                    <li><a href="{{URL::route('notification-privacy')}}" data-ajaxify="true">{{trans('notification.notifications')}}</a> </li>
                    <li><a data-ajaxify="true" href="{{URL::route('block-users')}}">{{trans('user.block-members')}}</a> </li>
                    <li><a data-ajaxify="true" href="{{URL::route('edit-profile')}}">{{trans('user.edit-profile')}}</a> </li>

                    @if(Config::get('page-design'))
                        <li><a data-ajaxify="true" href="{{URL::route('user-design')}}">{{trans('user.design-your-page')}}</a> </li>
                    @endif

                    @foreach(Menu::lists('account-settings') as $menu)
                        <li><a href="{{$menu->link}}" {{($menu->ajaxify) ? "data-ajaxify='true'" : null}} >{{$menu->name}}</a> </li>
                    @endforeach

                    <li><a data-ajaxify="true" href="{{URL::route('user-deactivate')}}">{{trans('user.deactivate-account')}}</a> </li>
                </ul>
        </div>
    </div>
</div>