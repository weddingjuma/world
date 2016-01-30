 <div id="topography">
        <div id="topography-cover"></div>

        <div class="container clearfix">
            <div class="left">
                <h3 class="title">{{trans('home.welcome-to-our-social-network')}}</h3>

                <div class="welcome-notes">
                    <div class="media">
                        <div class="media-object pull-left"><i class="icon ion-compose"></i></div>
                        <div class="media-body">
                            <h3>{{trans('home.share-content')}}</h3>
                            <span>{{trans('home.share-content-note')}}</span>
                        </div>
                    </div>

                    <div class="media">
                        <div class="media-object pull-left"><i style="color: rgb(15, 180, 146)" class="icon ion-chatboxes"></i></div>
                        <div class="media-body">
                            <h3>{{trans('home.discuss')}}</h3>
                            <span>{{trans('home.discuss-note')}}</span>
                        </div>
                    </div>

                    <div class="media">
                        <div class="media-object pull-left"><i class="icon ion-ios7-people-outline"></i></div>
                        <div class="media-body">
                            <h3>{{trans('home.find-people')}}</h3>
                            <span>{{trans('home.find-people-note')}}</span>
                        </div>
                    </div>
                </div>

                <!--<div class="social-authentication-links">
                    <a href="" class="facebook"><i class="icon ion-social-facebook"></i> Continue with facebook</a>
                    <a href="" class="twitter"><i class="icon ion-social-twitter"></i> Continue with twitter</a>
                </div>-->
            </div>
            <div class="right">
                <div class="login-social-links">
                    <a href="" data-toggle="modal" data-target="#signup-form-modal" class="email"><i class="icon ion-android-mail"></i> <span class="arrow-right"></span> {{trans('home.signup-with-mail')}}</a>
                </div>
                <form id="login-form" role="form" action="#" method="post">
                  <div class="message"></div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">{{trans('global.email-address')}}</label>
                    <input name="val[username]" type="text" id="exampleInputEmail1" placeholder="{{trans('user.enter-email')}}">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">{{trans('global.password')}}</label>
                    <input type="password" name="val[password]" id="exampleInputPassword1" placeholder="{{trans('global.password')}}">
                  </div>

                  <div class="checkbox">
                    <label>
                      <input name="val[keep]" type="checkbox"> {{trans('user.keep-me-login')}}
                    </label>
                  </div>
                  <button type="submit" class="btn btn-danger btn-sm">{{trans('global.login')}}</button> <a href="{{URL::route('forgot-password')}}">{{trans('user.forgot-password')}}?</a>
                </form>

                <div class="login-social-links">
                    <div class="panel">

                        @if(Config::get('enable-facebook-login'))
                        <a href="{{URL::route('facebook-auth')}}" class="facebook"><i class="icon ion-social-facebook"></i></a>
                        @endif

                        @if(Config::get('enable-twitter-login'))
                        <a href="{{URL::route('twitter-auth')}}" class="twitter"><i class="icon ion-social-twitter"></i> </a>
                        @endif

                        @if(Config::get('enable-google-login'))
                        <a style="background: #F25757" href="{{URL::route('google-auth')}}" class="google"><i class="icon ion-social-googleplus-outline"></i></a>
                        @endif

                        @if(Config::get('enable-vk-login'))
                        <a href="{{URL::route('vk-auth')}}" class="vk">
                            <img src="{{Theme::asset()->img('theme/images/vk.png')}}"/>
                        </a>
                        @endif
                    </div>



                </div>



                <div class="modal fade" id="signup-form-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <form id="signup-form" action="{{URL::route('signup')}}" role="form">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">{{trans('home.signup-for-account')}}</h4>
                          </div>
                          <div class="modal-body">

                              <div class="message"></div>
                              <div class="form-group">
                                      <label for="exampleInputEmail1">{{trans('user.your-full-name')}}</label>
                                      <input type="text" name="val[fullname]" class="form-control"  placeholder="{{trans('user.provide-your-fullname')}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="exampleInputEmail1">{{trans('user.choose-username')}}</label>
                                      <input type="text" name="val[username]" class="form-control"  placeholder="{{trans('user.choose-username')}}">
                                  </div>
                                  <div class="form-group">
                                      <label for="exampleInputEmail1">{{trans('global.email-address')}}</label>
                                      <input type="text" name="val[email_address]" class="form-control" id="exampleInputEmail1" placeholder="{{trans('user.enter-email')}}">
                                  </div>

                                  @if(Config::get('user-enable-birth-date', 1))
                                  {{Theme::section('user.birthdate',[
                                  'day' => Input::get('val.day'),
                                  'month' => Input::get('val.month'),
                                  'year' => Input::get('val.year')
                                  ])}}
                                  @endif
                                  <div class="form-group">
                                      <label for="exampleInputPassword1">{{trans('global.password')}}</label>
                                      <input type="password" name="val[password]" class="form-control" id="exampleInputPassword1" placeholder="{{trans('global.password')}}">
                                  </div>


                                  <div class="form-group">
                                      <label for="exampleInputFile">{{trans('user.i-am')}}:</label>
                                      <select style="width: 33%" name="val[genre]">
                                          <option value="">{{trans('global.genre')}}</option>
                                          <option value="male">{{trans('global.male')}}</option>
                                          <option value="female">{{trans('global.female')}}</option>
                                      </select>

                                      <label>{{trans('global.from')}}:</label>
                                      <select name="val[country]">
                                          <option value="">{{trans('user.select-country')}}</option>
                                          @foreach(Config::get('system.countries') as $country => $name)
                                          <option value="{{$country}}">{{$name}}</option>
                                          @endforeach
                                      </select>
                                  </div>


                                  @if(Config::get('enable-captcha'))

                                  <div class="control-group">
                                      <input placeholder="{{trans('user.captcha-text-info')}}" class="form-control" id="captcha-form" type="text" name="val[captcha]" autocomplete="off"/>
                                      <img style="width: 150px;border-radius: 5px;margin-top:5px" id="captcha" src="{{URL::to('/app/library/captchalib/captcha.php')}}"/>
                                      <i class="icon ion-ios7-reload"></i> <a href="#" onclick="
                                          document.getElementById('captcha').src='{{URL::to('/app/library/captchalib/')}}/captcha.php?'+Math.random();
                                          document.getElementById('captcha-form').focus();"
                                                                              id="change-image">{{trans('user.captcha-reload')}}</a>


                                  </div>
                                  @endif
                                  <div class="checkbox">
                                      <label>
                                          <input name="val[terms-and-condition]" type="checkbox"> {{trans('user.by-clicking-signup-button')}} <a href="{{URL::route('terms-and-condition')}}">{{trans('user.terms-and-conditions')}}</a>
                                      </label>
                              </div>
                          </div>
                          <div class="modal-footer">
                                <button type="submit" class="btn btn-success">{{trans('user.signup-now')}}</button><br/>
                          </div>

                      </form>

                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->



            </div>
        </div>
    </div>

    <div id="home-members-container">
        <div class="container clearfix">

                <ul class="nav">

                    @foreach($users as $user)
                        <li><a href="{{$user->present()->url()}}"><img src="{{$user->present()->getAvatar(100)}}"/> </a> </li>
                    @endforeach

                </ul>



        </div>
    </div>