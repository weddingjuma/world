<!DOCTYPE HTML>
<html>
<head>
    <meta name="keyword" content=""/>
    <meta name="keyword" content=""/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>{{$title}}</title>
    {{Theme::asset()->styles()}}
    <link rel="shortcut icon" type="image/x-icon" href="{{URL::to('')}}/favicon.ico" />
</head>
<body>

        {{Theme::section('theme/layouts.mobile-menu')}}
        <div id="left-content">
            <div class="title-pane">
                {{Config::get('site_title')}} AdminCP
            </div>
            {{Theme::section('theme/layouts.menu')}}
        </div>
        <div id="right-content">
            <div id="header">
                <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <a href="{{URL::route('admincp')}}">{{trans('admincp.dashboard')}}</a>
                        </li>
                        <li>
                            <a href="{{URL::to('/')}}">{{trans('admincp.goto-website')}}</a>
                        </li>

                        <li class="dropdown">
                            <a data-toggle="dropdown" class="dropdown-toggle" href=""><i class="icon ion-android-add"></i> {{trans('admincp.add')}}</a>
                            <ul class="dropdown-menu">
                                @foreach(Menu::lists('admincp-add') as $menu)
                                <li><a href="{{$menu->link}}">{{$menu->name}}</a> </li>
                                @endforeach

                                <li><a href="{{\URL::to('admincp/languages/add')}}">Add Language</a></li>
                                <li><a href="{{\URL::to('admincp/user/custom-field/add')}}">Add Custom Fields</a></li>
                                <li><a href="{{\URL::to('admincp/pages/create/category')}}">Add Page Categories</a> </li>
                                <li><a href="{{\URL::to('admincp/games/add')}}">Add Games</a> </li>
                                <li><a href="{{\URL::to('admincp/games/create/category')}}">Add Games Categories</a> </li>
                                <li><a href="{{\URL::to('admincp/helps/add')}}">Add Help</a></li>
                                <li><a href="{{\URL::to('admincp/ads')}}">Manage Ads</a> </li>
                                <li><a href="{{\URL::to('admincp/newsletter/add')}}">Add Newsletter</a> </li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right">

                        <li class="profile-link">
                            <a  href="{{Auth::user()->present()->url()}}"><img src="{{Auth::user()->present()->getAvatar(50)}}"/> </a>

                        </li>

                        <li class="logout-link">
                            <a href="{{URL::route('user-logout')}}">{{trans('global.logout')}}</a>
                        </li>
                    </ul>
                </nav>

                <ul class="nav mobile-nav navbar-left">
                    <li>
                        <a href="{{URL::route('admincp')}}">{{trans('admincp.dashboard')}}</a>
                    </li>
                    <li>
                        <a href="{{URL::to('/')}}">{{trans('admincp.goto-website')}}</a>
                    </li>

                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href=""><i class="icon ion-android-add"></i> {{trans('admincp.add')}}</a>
                        <ul class="dropdown-menu">
                            @foreach(Menu::lists('admincp-add') as $menu)
                            <li><a href="{{$menu->link}}">{{$menu->name}}</a> </li>
                            @endforeach

                            <li><a href="{{\URL::to('admincp/languages/add')}}">Add Language</a></li>
                            <li><a href="{{\URL::to('admincp/user/custom-field/add')}}">Add Custom Fields</a></li>
                            <li><a href="{{\URL::to('admincp/pages/create/category')}}">Add Page Categories</a> </li>
                            <li><a href="{{\URL::to('admincp/games/add')}}">Add Games</a> </li>
                            <li><a href="{{\URL::to('admincp/games/create/category')}}">Add Games Categories</a> </li>
                            <li><a href="{{\URL::to('admincp/helps/add')}}">Add Help</a></li>
                            <li><a href="{{\URL::to('admincp/ads')}}">Manage Ads</a> </li>
                            <li><a href="{{\URL::to('admincp/newsletter/add')}}">Add Newsletter</a> </li>
                        </ul>
                    </li>
                </ul>

                <a href="" class="mobile-menu-trigger"><i class="icon ion-navicon-round"></i></a>
            </div>

            <div class="main-content">
                {{$content}}
            </div>
        </div>

</body>
{{Theme::asset()->scripts()}}
</html>