<div class="left-nav mobile-menu">

    <ul class="nav">
        <li class="{{($activePage == 'dashboard') ? 'active' : null}}">
            <a href="{{URL::route('admincp')}}">{{trans('admincp.dashboard')}}</a>
        </li>
        <li class=" {{($activePage == 'users') ? 'active' : null}}">
            <a  href="">{{trans('admincp.users')}}</a>
            <ul>
                @foreach(Menu::lists('admincp-users') as $menu)

                <li><a href="{{$menu->link}}">{{$menu->name}}</a> </li>
                @endforeach
            </ul>
        </li>
        <li class="{{($activePage == 'configurations') ? 'active' : null}}">
            <a  href="">{{trans('admincp.configurations')}}</a>
            <ul >
                @foreach(Menu::lists('admincp-configuration') as $menu)

                <li><a href="{{$menu->link}}">{{$menu->name}}</a> </li>
                @endforeach

                <li><a href="{{URL::route('admincp-update-configuration')}}">{{trans('admincp.update-configuration')}}</a> </li>
            </ul>
        </li>
        <li class=" {{($activePage == 'theme') ? 'active' : null}}">
            <a  href="">{{trans('admincp.theme-management')}}</a>
            <ul>
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
            <a  href="{{$menu->link}}">{{$menu->name}}</a>

            @if($menu->hasMenus())
            <ul class="">
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
