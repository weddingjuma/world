    <header id="header" class="navbar  navbar-fixed-top" role="banner">

          <div class="container">
            <div class="navbar-header">

              <a href="{{URL::route('admincp')}}" class="navbar-brand"><img src="{{Theme::asset()->img('theme/images/logo.png')}}"/> </a>
            </div>
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

                    <li class="dropdown account-menu">
                        <a data-toggle="dropdown" class="dropdown-toggle" href=""><img src="{{Auth::user()->present()->getAvatar(50)}}"/> </a>
                        <ul class="dropdown-menu">

                            <li>
                                <a href="{{URL::route('user-logout')}}">{{trans('global.logout')}}</a>
                            </li>
                        </ul>
                    </li>
              </ul>
            </nav>


          </div>
        </header>

