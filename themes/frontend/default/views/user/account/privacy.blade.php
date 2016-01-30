<div class="box">
    <div class="box-title">{{trans('user.security-privacy')}}</div>
    <div class="box-content">
        <form class="form-horizontal" role="form" action="" method="post">
            @if(!Config::get('disable-follow'))
                <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('user.who-can-follow-you')}}</label>
                    <div class="col-sm-7 ">

                        <select name="val[follow-me]" class="form-control">
                            <option {{($user->present()->privacy('follow-me', 1) == 1)? 'selected':null}} value="1">{{trans('user.everyone')}}</option>
                            <option {{($user->present()->privacy('follow-me', 1) == 2)? 'selected':null}} value="2">{{trans('user.only-friends')}}</option>
                            <option {{($user->present()->privacy('follow-me', 1) == 3)? 'selected':null}} value="3">{{trans('user.nobody')}}</option>
                        </select>
                    </div>

                </div>
            @endif

            @if(Config::get('user-enable-birth-date', 1))
                <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('user.who-can-see-birth')}}</label>
                    <div class="col-sm-7 ">

                        <select name="val[view-birth]" class="form-control">
                            <option {{($user->present()->privacy('view-birth', 'nobody') == 'public')? 'selected':null}} value="public">{{trans('user.everyone')}}</option>
                            <option {{($user->present()->privacy('view-birth', 'nobody') == 'friends')? 'selected':null}} value="friends">{{trans('connection.friends')}}</option>
                            <option {{($user->present()->privacy('view-birth', 'nobody') == 'friend-follower')? 'selected':null}} value="friend-follower">{{trans('connection.friends-followers')}}</option>
                            <option {{($user->present()->privacy('view-birth', 'nobody') == 'nobody')? 'selected':null}} value="nobody">{{trans('user.nobody')}}</option>
                        </select>
                    </div>

                </div>
            @endif


            <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('user.who-can-view-profile')}}</label>
                    <div class="col-sm-7 ">

                        <select name="val[view-profile]" class="form-control">
                            <option {{($user->present()->privacy('view-profile', 'friend&follower') == 'public')? 'selected':null}} value="public">{{trans('user.everyone')}}</option>
                            <option {{($user->present()->privacy('view-profile', 'friend&follower') == 'friends')? 'selected':null}} value="friends">{{trans('connection.friends')}}</option>
                            <option {{($user->present()->privacy('view-profile', 'friend&follower') == 'friend-follower')? 'selected':null}} value="friend-follower">{{trans('connection.friends-followers')}}</option>
                            <option {{($user->present()->privacy('view-profile', 'friend&follower') == 'nobody')? 'selected':null}} value="nobody">{{trans('user.nobody')}}</option>
                        </select>
                    </div>

            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{{trans('user.who-send-you-message')}}</label>
                <div class="col-sm-7 ">

                    <select name="val[send-message]" class="form-control">
                        <option {{($user->present()->privacy('send-message', 'public') == 'public')? 'selected':null}} value="public">{{trans('user.everyone')}}</option>
                        <option {{($user->present()->privacy('send-message', 'public') == 'friends')? 'selected':null}} value="friends">{{trans('connection.friends')}}</option>
                        <option {{($user->present()->privacy('send-message', 'public') == 'friend-follower')? 'selected':null}} value="friend-follower">{{trans('connection.friends-followers')}}</option>
                        <option {{($user->present()->privacy('send-message', 'public') == 'nobody')? 'selected':null}} value="nobody">{{trans('user.nobody')}}</option>
                    </select>
                </div>

            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{{trans('user.who-can-post-on-timeline')}}</label>
                <div class="col-sm-7 ">

                    <select name="val[timeline-post]" class="form-control">
                        <option {{($user->present()->privacy('timeline-post', 'public') == 'public')? 'selected':null}} value="public">{{trans('user.everyone')}}</option>
                        <option {{($user->present()->privacy('timeline-post', 'public') == 'friends')? 'selected':null}} value="friends">{{trans('connection.friends')}}</option>
                        <option {{($user->present()->privacy('timeline-post', 'public') == 'friend-follower')? 'selected':null}} value="friend-follower">{{trans('connection.friends-followers')}}</option>
                        <option {{($user->present()->privacy('timeline-post', 'public') == 'nobody')? 'selected':null}} value="nobody">{{trans('user.nobody')}}</option>
                    </select>
                </div>

            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{{trans('user.turn-off-friend-request')}}</label>
                <div class="col-sm-7 ">

                    <select name="val[turnoff-friend-request]" class="form-control">
                        <option {{($user->present()->privacy('turnoff-friend-request', '0') == '0')? 'selected':null}} value="0">{{trans('global.no')}}</option>
                        <option {{($user->present()->privacy('turnoff-friend-request', '0') == '1')? 'selected':null}} value="1">{{trans('global.yes')}}</option>
                    </select>
                </div>

            </div>


                      <div class="divider"></div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-sm btn-danger">{{trans('global.save')}}</button>

                        </div>
                      </div>

        </form>
    </div>
</div>