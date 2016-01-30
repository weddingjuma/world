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

        {{Theme::section('layouts.header')}}

            <div id="content-container">
                <div class="container">
                    <div class="left-nav">
                        <div class="title">Menu</div>
                        <ul class="nav">
                            <li class="{{($activePage == 'dashboard') ? 'active' : null}}">
                                <a href="{{URL::route('admincp')}}">{{trans('admincp.dashboard')}}</a>
                            </li>
                            <li class="dropdown {{($activePage == 'users') ? 'active' : null}}">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="">{{trans('admincp.users')}}</a>
                                <ul class="dropdown-menu">
                                    @foreach(Menu::lists('admincp-users') as $menu)

                                        <li><a href="{{$menu->link}}">{{$menu->name}}</a> </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="dropdown {{($activePage == 'configurations') ? 'active' : null}}">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="">{{trans('admincp.configurations')}}</a>
                                <ul class="dropdown-menu">
                                    @foreach(Menu::lists('admincp-configuration') as $menu)

                                        <li><a href="{{$menu->link}}">{{$menu->name}}</a> </li>
                                    @endforeach

                                    <li><a href="{{URL::route('admincp-update-configuration')}}">{{trans('admincp.update-configuration')}}</a> </li>
                                </ul>
                            </li>
                            <li class=" dropdown {{($activePage == 'theme') ? 'active' : null}}">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="">{{trans('admincp.theme-management')}}</a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{URL::route('admincp-theme')}}?type=frontend">{{trans('admincp.frontend-themes')}}</a> </li>
                                    <li><a href="{{URL::route('admincp-theme')}}?type=admincp">{{trans('admincp.admincp-themes')}}</a> </li>
                                    @foreach(Menu::lists('admincp-themes') as $menu)

                                        <li><a href="{{$menu->link}}">{{$menu->name}}</a> </li>
                                    @endforeach
                                </ul>
                            </li>
                            <li class="{{($activePage == 'addon') ? 'active' : null}}">
                                <a href="{{URL::route('admincp-addon')}}">{{trans('admincp.addon-management')}}</a>
                            </li>

                            @foreach(Menu::lists('admincp-menu') as $menu)
                                <li class=" {{($activePage == $menu->id) ? 'active' : null}} {{($menu->hasMenus()) ? 'dropdown' : null}}">
                                    <a class=" {{($menu->hasMenus()) ? 'dropdown' : null}}" {{($menu->hasMenus()) ? 'data-toggle="dropdown"':null}} href="{{$menu->link}}">{{$menu->name}}</a>

                                    @if($menu->hasMenus())
                                        <ul class="dropdown-menu">
                                            @foreach($menu->listMenus() as $subMenu)
                                                <li>
                                                    <a href="{{$subMenu->link}}">{{$subMenu->name}}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach

                            <li>
                                <a href="{{URL::route('admincp-database-update')}}">{{trans('admincp.update-database')}}</a>
                            </li>

                        </ul>
                    </div>

                    <div class="right-content">
                        {{$content}}
                    </div>
                </div>
            </div>
        {{Theme::section('layouts.footer')}}


    </body>
    {{Theme::asset()->scripts()}}
</html>


