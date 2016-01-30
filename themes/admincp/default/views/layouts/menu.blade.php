<div id="admin-side-title">{{trans('admincp::global.menu')}}</div>
<ul id="side-menu" class="menu">
    <li>

        <a href="{{URL::route('admincp')}}">
            <span class="icon"><i class="fa fa-th"></i></span>
            Dashboard
        </a>
    </li>
    @foreach(Menu::lists('admin-menu') as $menu)
        <li>

            <a class="{{($menu->hasMenus()) ? 'has-menu' : null}}" href="{{$menu->link}}">
                <span class="icon"><i class="fa fa-th"></i></span>
                {{$menu->name}}
            </a>

            @if($menu->hasMenus())

                <ul class="sub-menu">
                    @foreach($menu->listMenus() as $subMenu)
                        <li>
                            <a href="{{$subMenu->link}}">{{$subMenu->name}}</a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach

</ul>