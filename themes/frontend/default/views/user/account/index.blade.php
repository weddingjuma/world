            <div class="box">
                <div class="box-title">
                    {{trans('user.account-settings')}}
                </div>

                <div class="box-content">
                    @if(!empty($message))
                        <div class="alert alert-danger">{{$message}}</div>
                    @endif
                    <form action="" method="post" id="account-form" class="form-horizontal" role="form">
                      <div class="form-group">
                        <label for="inputFullname" class="col-sm-3 control-label">{{trans('user.your-full-name')}}</label>
                        <div class="col-sm-7">
                          <input type="text" name="val[fullname]" value="{{$user->fullname}}" class="form-control" id="inputFullname" placeholder="{{trans('user.your-full-name')}}">
                            <p class="help-block">{{trans('user.change-fullname-helper')}}</p>
                        </div>
                      </div>

                      @if(Config::get('can-change-username'))
                            <div class="form-group">
                                <label for="inputUsername" class="col-sm-3 control-label">{{trans('global.username')}}</label>
                                <div class="col-sm-7">
                                  <input type="text" name="val[username]" value="{{$user->username}}" class="form-control" id="inputUsername" placeholder="{{trans('global.username')}}">
                                  <p class="help-block">
                                      <div class="alert alert-warning">
                                        {{trans('user.change-username-helper')}}
                                        @if(Auth::user()->verified == 1 and Config::get('remove-verify-badge-username'))
                                        {{trans('user.change-username-remove-badge')}}
                                        @endif
                                      </div>
                                  </p>
                                </div>
                            </div>
                      @endif

                        <div class="form-group">
                            <label for="inputEmail" class="col-sm-3 control-label">{{trans('global.email-address')}}</label>
                            <div class="col-sm-7">
                                <input type="text" name="val[email]" value="{{$user->email_address}}" class="form-control" id="inputEmail" placeholder="{{trans('global.email-address')}}">

                            </div>
                        </div>

                        @if(Config::get('user-enable-birth-date', 1))
                            <div class="divider"></div>
                            <div class="" style="padding:0 20px">

                                {{Theme::section('user.birthdate',[
                                'day' => $user->birth_day,
                                'month' => $user->birth_month,
                                'year' => $user->birth_year
                                ])}}

                                <p class="help-block"><a data-ajaxify="true" href="{{URL::route('user-account-privacy')}}"><i class="icon ion-ios7-locked"></i> {{trans('user.control-who-see-birthdate')}}</a> </p>
                            </div>
                        @endif

                      <div class="divider"></div>
                       <p class="help-block">{{trans('user.change-password-helper')}}</p>
                      <div class="form-group">
                        <label for="inputPassword" class="col-sm-3 control-label">{{trans('user.current-password')}}</label>
                        <div class="col-sm-7">
                          <input type="password" name="val[currentpassword]" class="form-control" id="inputPassword" placeholder="{{trans('user.current-password')}}">
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="inputNewPassword" class="col-sm-3 control-label">{{trans('user.new-password')}}</label>
                        <div class="col-sm-7">
                          <input type="password" name="val[newpassword]" class="form-control" id="inputNewPassword" placeholder="{{trans('user.new-password')}}">
                        </div>
                      </div>

                      <div class="divider"></div>

                        <div class="form-group">
                                <label for="inputUsername" class="col-sm-3 control-label">{{trans('global.genre')}}</label>
                                <div class="col-sm-7">
                                  <select name="val[genre]">
                                        <option {{($user->genre == 'male') ? 'selected' :null}} value="male">{{trans('global.male')}}</option>
                                      <option {{($user->genre == 'female') ? 'selected' :null}} value="female">{{trans('global.female')}}</option>
                                    </select><br/>
                                </div>
                        </div>

                        <div class="divider"></div>


                        <div class="form-group">
                            <label for="inputUsername" class="col-sm-3 control-label">{{trans('user.change-location')}}</label>
                            <div class="col-sm-7">
                                <select class="form-control" name="val[country]">
                                    <option value="">{{trans('user.select-country')}}</option>
                                    @foreach(Config::get('system.countries') as $country => $name)
                                    <option {{($user->country == $country) ? 'selected' : null}} value="{{$country}}">{{$name}}</option>
                                    @endforeach
                                </select><br/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="inputUsername" class="col-sm-3 control-label">{{trans('global.city')}}</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="val[city]" value="{{$user->city}}"/>
                            </div>
                        </div>
                        <div class="divider"></div>

                        <div class="form-group">
                            <label for="inputUsername" class="col-sm-3 control-label">{{trans('user.language')}}</label>
                            <div class="col-sm-7">
                                <select class="form-control" name="val[language]">
                                    <?php $lang = $user->present()->privacy('lang', \Cache::get('active-language', 'en'))?>
                                    @foreach($languages as $language)
                                        <option {{($language->var == $lang) ? 'selected' : null}} value="{{$language->var}}">{{$language->name}}</option>
                                    @endforeach
                                </select><br/>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{trans('user.bio')}}</label>
                            <div class="col-sm-7">
                                <textarea name="val[bio]" class="form-control">{{$user->bio}}</textarea>
                            </div>
                        </div>

                        <div class="divider"></div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">{{trans('user.change-photo')}}</label>
                            <div class="col-sm-7">
                                <select name="val[avatar_type]" class="form-control">
                                    <option {{($user->present()->privacy('avatar_type', 0) == 0) ? 'selected' :null}} value="0">{{trans('user.default')}}</option>
                                    <option {{($user->present()->privacy('avatar_type', 0) == 1) ? 'selected' :null}} value="1">{{trans('user.uploaded-photo')}}</option>
                                </select>
                                <p class="help-block">{{trans('user.prefer-avatar')}}</p>
                                <div class="media">
                                    <div class="media-object pull-left">
                                        <img width="70" src="{{$user->present()->getAvatar(100)}}"/>
                                    </div>
                                    <div class="media-body">
                                                <span style=""  class=" fileupload fileupload-exists" data-provides="fileupload">

                                                     <a    class=" btn btn-danger btn-file">
                                                         <span class="fileupload-new">{{trans('user.change-photo')}}</span>
                                                         <span class="fileupload-exists">{{trans('user.change-photo')}}</span>
                                                         <input title="" id="image-input" class="" type="file" name="image">
                                                     </a>


                                                 </span>

                                                <p class="help-block">{{trans('user.change-photo-info')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="divider"></div>

                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-sm btn-success">{{trans('user.save-account')}}</button>
                          <a href="{{URL::route('user-deactivate')}}" data-warning-message="{{trans('user.are-you-sure')}}"  class="btn btn-danger btn-sm pull-right">{{trans('user.deactivate-account')}}</a>
                        </div>
                      </div>
                    </form>

                </div>

            </div>