<form class="form-horizontal" action="" method="post">
    <div class="box">
        <div class="box-title">{{trans('notification.site-notifications')}}</div>
        <div class="box-content">
            <p class="help-block">{{trans('notification.receive-site')}}</p>

            <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('notification.someone-follow-you')}}</label>
                    <div class="col-sm-5 ">

                        <select name="val[notify-follow-me]" class="form-control input-sm">
                            <option {{($user->present()->privacy('notify-follow-me', 1) == 1)? 'selected':null}} value="1">{{trans('notification.on')}}</option>
                            <option {{($user->present()->privacy('notify-follow-me', 1) ==  0)? 'selected':null}} value="0">{{trans('notification.off')}}</option>
                        </select>
                    </div>

            </div>

            <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('notification.someone-mention-you')}}</label>
                    <div class="col-sm-5 ">

                        <select name="val[notify-mention-post]" class="form-control input-sm">
                            <option {{($user->present()->privacy('notify-mention-post', 1) == 1)? 'selected':null}} value="1">{{trans('notification.on')}}</option>
                            <option {{($user->present()->privacy('notify-mention-post', 1) ==  0)? 'selected':null}} value="0">{{trans('notification.off')}}</option>
                        </select>
                    </div>

            </div>


            <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('notification.someone-comment')}}</label>
                    <div class="col-sm-5 ">

                        <select name="val[notify-comment-post]" class="form-control input-sm">
                            <option {{($user->present()->privacy('notify-comment-post', 1) == 1)? 'selected':null}} value="1">{{trans('notification.on')}}</option>
                            <option {{($user->present()->privacy('notify-comment-post', 1) ==  0)? 'selected':null}} value="0">{{trans('notification.off')}}</option>
                        </select>
                    </div>

            </div>

            <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('notification.someone-share-post')}}</label>
                    <div class="col-sm-5 ">

                        <select name="val[notify-share-post]" class="form-control input-sm">
                            <option {{($user->present()->privacy('notify-share-post', 1) == 1)? 'selected':null}} value="1">{{trans('notification.on')}}</option>
                            <option {{($user->present()->privacy('notify-share-post', 1) ==  0)? 'selected':null}} value="0">{{trans('notification.off')}}</option>
                        </select>
                    </div>

            </div>

            <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('notification.someone-like-post')}}</label>
                    <div class="col-sm-5 ">

                        <select name="val[notify-like-post]" class="form-control input-sm">
                            <option {{($user->present()->privacy('notify-like-post', 1) == 1)? 'selected':null}} value="1">{{trans('notification.on')}}</option>
                            <option {{($user->present()->privacy('notify-like-post', 1) ==  0)? 'selected':null}} value="0">{{trans('notification.off')}}</option>
                        </select>
                    </div>

            </div>
        </div>

        <div class="divider"></div>
        <div class="box-title">{{trans('notification.email-notification')}}</div>
        <div class="box-content">
            <p class="help-block">{{trans('notification.receive-mail')}}</p>

            <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('notification.someone-follow-you')}}</label>
                    <div class="col-sm-5 ">

                        <select name="val[email-notify-follow-me]" class="form-control input-sm">
                            <option {{($user->present()->privacy('email-notify-follow-me', 1) == 1)? 'selected':null}} value="1">{{trans('notification.on')}}</option>
                            <option {{($user->present()->privacy('email-notify-follow-me', 1) ==  0)? 'selected':null}} value="0">{{trans('notification.off')}}</option>
                        </select>
                    </div>

            </div>

           <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('notification.someone-mention-you')}}</label>
                    <div class="col-sm-5 ">

                        <select name="val[email-notify-mention-post]" class="form-control input-sm">
                            <option {{($user->present()->privacy('email-notify-mention-post', 1) == 1)? 'selected':null}} value="1">{{trans('notification.on')}}</option>
                            <option {{($user->present()->privacy('email-notify-mention-post', 1) ==  0)? 'selected':null}} value="0">{{trans('notification.off')}}</option>
                        </select>
                    </div>

            </div>



            <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('notification.friend-request')}}</label>
                    <div class="col-sm-5 ">

                        <select name="val[email-notify-friend-request]" class="form-control input-sm">
                            <option {{($user->present()->privacy('email-notify-friend-request', 1) == 1)? 'selected':null}} value="1">{{trans('notification.on')}}</option>
                            <option {{($user->present()->privacy('email-notify-friend-request', 1) ==  0)? 'selected':null}} value="0">{{trans('notification.off')}}</option>
                        </select>
                    </div>

            </div>

            <div class="form-group">
                    <label class="col-sm-4 control-label">{{trans('notification.someone-comment')}}</label>
                    <div class="col-sm-5 ">

                        <select name="val[email-notify-comment-post]" class="form-control input-sm">
                            <option {{($user->present()->privacy('email-notify-comment-post', 1) == 1)? 'selected':null}} value="1">{{trans('notification.on')}}</option>
                            <option {{($user->present()->privacy('email-notify-comment-post', 1) ==  0)? 'selected':null}} value="0">{{trans('notification.off')}}</option>
                        </select>
                    </div>

            </div>


            <div class="divider"></div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-sm btn-danger">{{trans('global.save')}}</button>

                        </div>
                      </div>

        </div>
    </div>
</form>