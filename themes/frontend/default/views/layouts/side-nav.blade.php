<nav id="sidebar"  class="" role="navigation">

    <ul  class="">
        <li>
           @if(Auth::check())
                 <span class="search-box">
                    <form action="{{URL::route('search')}}" method="get">
                        <input type="text" placeholder="{{trans('search.placeholder')}}" name="term"/>
                        <button class=""><i class="icon ion-search"></i></button>
                    </form>
                </span>
           @endif
        </li>
        @if(Auth::check())
        <li ><a data-ajaxify="true" href="{{URL::route('user-home')}}"> <i class="icon ion-home"></i> {{trans('global.home')}}</a></li>
        @if(Auth::check())
        <li ><a data-ajaxify="true" href="{{Auth::user()->present()->url()}}" data-ajaxify="true"> <i class="icon ion-user"></i> {{trans('user.my-profile')}}</a></li>
        @endif
        @foreach(Menu::lists('site-menu') as $menu)
        <li><a href="{{$menu->link}}" {{($menu->ajaxify)? 'data-ajaxify="true"' : null}}>{{$menu->icon}} {{$menu->name}}</a> </li>
        @endforeach
        @endif


        @if(Auth::check())
        <li ><a href="{{URL::route('user-account')}}"> <i class="icon ion-home"></i> {{trans('user.account-settings')}}</a></li>
        <li ><a href="{{URL::route('user-logout')}}"> <i class="icon ion-home"></i> {{trans('global.logout')}}</a></li>
        @else
        <li><a href="{{URL::to('')}}">{{trans('global.login')}}</a> </li>
        @endif
    </ul>



</nav>