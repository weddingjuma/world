<div class="container page-content clearfix">

        <div class="left-column">

            <div class="box">
                <div class="box-title">{{trans('invite.invite-friends')}}</div>
                <div class="box-content">
                   {{Theme::extend("invite-extend")}}
                   @if($message)
                        <div class="alert alert-success">{{$message}}</div>
                   @endif
                   <div class="alert alert-info">
                       {{trans('invite.invite-note')}}
                   </div>

                   <form class="form-horizontal" method="post" action="">
                       <div class="form-group">
                           <label for="inputFullname" class="col-sm-3 control-label">{{trans('global.from')}} :</label>
                           <div class="col-sm-7">
                               <input type="text" name="val[from]" value="{{$user->email_address}}" class="form-control" id="inputFullname" disabled>

                           </div>
                       </div>
                       <div class="form-group">
                           <label for="inputFullname" class="col-sm-3 control-label">{{trans('global.subject')}} : </label>
                           <div class="col-sm-7">
                               {{$user->fullname}} invites you to {{Config::get('site_title')}}

                           </div>
                       </div>
                       <div class="form-group">
                           <label for="inputFullname" class="col-sm-3 control-label">{{trans('global.to')}}:</label>
                           <div class="col-sm-7">
                               <textarea name="val[to]" class="form-control" style="height: 200px"></textarea>
                               <p class="help-block">{{trans('invite.message-note')}}</p>
                           </div>
                       </div>

                       <div class="form-group">
                           <div class="col-sm-offset-2 col-sm-10">
                               <button type="submit" class="btn btn-sm btn-success">{{trans('invite.send-invitation')}}</button>

                           </div>
                       </div>
                   </form>
                </div>
            </div>

        </div>

        <div class="right-column">
            {{Theme::section('user.side-info')}}

            {{Theme::widget()->get('invite')}}

        </div>
    </div>