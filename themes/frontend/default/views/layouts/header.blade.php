<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="{{$site_description}}">
        <meta name="keywords" content="{{$site_keywords}}">
        <!--SEO--->
        <meta property="og:title" content="{{$ogTitle}}" />
        <meta property="og:image" url="{{$ogImage}}" />
        <meta property="og:description" content="{{$site_description}}"/>
        <meta property="og:type" content="{{$ogType}}"/>
        <meta property="og:url" content="{{$ogUrl}}"/>
        <meta property="og:site_name" content="{{$ogSiteName}}"/>

        <!--Site title--->
        <title>{{$title}}</title>
        <link rel="shortcut icon" type="image/x-icon" href="{{URL::to('')}}/favicon.ico" />
        {{Theme::asset()->styles()}}

        {{Config::get('google-analytics-code', '')}}

    </head>
    <body>

    <header id="header" class="navbar navbar-default  navbar-fixed-top" role="banner">

          <div class="container">
            <div class="navbar-header">

              <a title="Go to home" href="{{URL::to('/')}}" class="navbar-brand"><img src="{{Theme::asset()->img('theme/images/logo.png')}}"/> </a>
            </div>
            <nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
              <ul class="nav navbar-nav search-box-container">



                   <li>
                       @if(!Config::get('remove-search-bar-from-home') or (Config::get('remove-search-bar-from-home') and Request::segment(1) != ''  and !in_array(Request::segment(1), ['forgot-password', 'contact', 'developers', 'about', 'disclaimer', 'terms'])))
                       <form id="search-box" class="search-box" action="{{URL::route('search')}}">

                           <input autocomplete="off" title="{{trans('search.placeholder')}}"   type="text" name="term" value="{{$searchRepository->term}}" placeholder="{{trans('search.placeholder')}}"/>
                           <button><i class="icon ion-search"></i> </button>

                           <div class="dropdown header-dropdown-box">
                                <div class="dropdown-content">

                                </div>
                                <a href="" class="footer-link">
                                    <img class="indicator" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/>
                                    {{trans('search.see-all-results')}}
                                </a>

                                <a class="close-button" href=""><i class="icon ion-close"></i></a>
                           </div>
                       </form>
                       @endif
                   </li>
              </ul>
              @if(Auth::check())

                        <ul class="nav navbar-nav navbar-right">

                            @if(Auth::check())
                            <li class="dropdown" id="main-menu">
                                <a  class="dropdown-toggle"  data-toggle="dropdown" href="">
                                    <i class="icon ion-navicon"></i> {{trans('global.explore')}}
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach(Menu::lists('site-menu') as $menu)
                                    <li><a href="{{$menu->link}}" {{($menu->ajaxify)? 'data-ajaxify="true"' : null}}>{{$menu->icon}} {{$menu->name}}</a> </li>
                                    @endforeach
                                </ul>
                            </li>
                            @endif
                        <li class="header-nav">
                            <a href="{{URL::route('user-home')}}" data-ajaxify="true"><i class="icon ion-home"></i></a>
                        </li>



                        <li id="notification-link" class="notification-link">
                            <?php $countNotification = $notificationRepository->count()?>
                            <a href="" id="notification-dropdown-trigger" class="notification-dropdown-trigger"><i class="icon ion-ios7-bell-outline"></i>
                                {{($countNotification) ? '<span>'.$countNotification.'</span>' : null}}
                            </a>
                            <div class="notification-dropdown header-dropdown-box">
                                <div class="content">

                                </div>
                                <a href="{{URL::route('notifications')}}" data-ajaxify="true" class="footer-link">
                                    <img class="indicator" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/>
                                    {{trans('notification.see-all')}}
                                </a>

                                <a class="close-button" href=""><i class="icon ion-close"></i></a>
                            </div>
                        </li>
                        <li class="notification-link">
                            <?php $countRequest = $connectionRepository->countFriendRequest()?>
                            <a class="friend-request-trigger" id='friend-request-trigger' href="{{URL::route('friend-requests')}}"><i class="icon ion-person-stalker"></i> {{(!empty($countRequest)) ?'<span>'.$countRequest. '</span>' : null}}</a>

                            <div class="request-dropdown header-dropdown-box">
                                <div class="content">

                                </div>
                                <a href="{{URL::route('friend-requests')}}" data-ajaxify="true" class="footer-link">
                                    <img class="indicator" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/>
                                    {{trans('connection.see-all-request')}}
                                </a>

                                <a class="close-button" href=""><i class="icon ion-close"></i></a>
                            </div>
                        </li>
                        <li id="message-dropdown-link" class="notification-link">
                            <?php $countMessage = app('App\\Repositories\\MessageRepository')->countNew()?>
                            <a href="" class="new-messages-trigger" id='new-messages-trigger'><i class="icon ion-ios7-chatboxes-outline"></i>
                                {{($countMessage) ? '<span>'.$countMessage.'</span>' : null}}
                            </a>

                            <div class="message-dropdown header-dropdown-box">
                                <div class="content">

                                </div>
                                <a href="{{URL::route('messages')}}" data-ajaxify="true" class="footer-link">
                                    <img class="indicator" src="{{Theme::asset()->img('theme/images/loading.gif')}}"/>
                                    {{trans('message.see-all-messages')}}
                                </a>

                                <a class="close-button" href=""><i class="icon ion-close"></i></a>
                            </div>
                        </li>
                        <li class="dropdown account-menu notification-link">
                            <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" href="">
                                <img width="20" height="20" src="{{Auth::user()->present()->getAvatar(30)}}"/>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach(Menu::lists('account-menu') as $menu)
                                    <li><a href="{{$menu->link}}" {{($menu->ajaxify) ? "data-ajaxify='true'" : null}} >{{$menu->name}}</a> </li>
                                @endforeach
                                <li><a href="{{$loggedInUser->present()->url()}}" data-ajaxify="true">{{trans('user.my-profile')}}</a></li>
                                <li><a href="{{URL::route('user-account')}}" data-ajaxify="true">{{trans('user.account-settings')}}</a> </li>

                                @if(Auth::user()->isAdmin())

                                    <li><a href="{{URL::route('admincp')}}">{{trans('admincp.admin-cp')}}</a> </li>
                                @endif

                                <li class="divider"></li>
                                <li><a href="{{URL::route('helps')}}">{{trans('help.help')}}</a> </li>
                                <li>
                                    <a href="{{URL::route('user-logout')}}">{{trans('global.logout')}}</a>
                                </li>
                            </ul>
                        </li>
                  </ul>
              @else
                    <ul id="change-language" class="navbar-nav nav navbar-right">
                        @if(Request::segment(1))
                            <li>
                                <a href="{{URL::to('/')}}">{{trans('user.login-signup-here')}}</a>
                            </li>
                        @endif
                        <li class="dropdown">
                            <a data-toggle="dropdown" data-hover="dropdown" class="dropdown-toggle" href="">{{trans('global.change-language')}} <i class="caret"></i></a>
                            <ul class="dropdown-menu">
                                @foreach($languages as $language)
                                    <li><a href="{{URL::route('change-language', ['lang' => $language->var])}}">{{$language->name}}</a> </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>

              @endif
            </nav>

                <ul id="header-mobile-nav" class=" nav navbar-nav visible-xs navbar-right">
                    @if(Auth::check())
                        <li class="notification-link">
                            <a class="notification-dropdown-trigger" href="{{URL::route('notifications')}}">
                                <i class="icon ion-ios7-bell-outline"></i>
                                {{($countNotification) ? '<span>'.$countNotification.'</span>' : null}}
                            </a>

                        </li>
                    <li class="notification-link">
                        <?php $countRequest = $connectionRepository->countFriendRequest()?>
                        <a class="friend-request-trigger" id='' href="{{URL::route('friend-requests')}}" data-ajaxify="true"><i class="icon ion-person-stalker"></i> {{(!empty($countRequest)) ?'<span>'.$countRequest. '</span>' : null}}</a>
                    </li>
                        <li class="notification-link">
                            <a class="new-messages-trigger" href="{{URL::route('messages')}}" id=''><i class="icon ion-ios7-chatboxes-outline"></i>
                                {{($countMessage) ? '<span>'.$countMessage.'</span>' : null}}
                            </a>
                        </li>
                    @endif
                    <li class="mobile-menu-toggle notification-link" id="mobile-">
                      <a href="#sidebar" id="sidebar-trigger"   data-canvas="body">
                        <i class="icon ion-navicon"></i>
                      </a>
                    </li>
                </ul>
          </div>
        </header>

    {{Theme::section('layouts.side-nav')}}