<div class="container">
    <div class="" id="static-content">
        <div class="static-note">
            <div class="note-content">{{trans('socialauth.need-info')}}</div>
            <div class="arrow-down"></div>
        </div>

        <div class="main-content">
            <div class="alert alert-info" style="font-size: 13px">
                {{trans('socialauth.info.note')}}
            </div>

            @if($message)
                <div class="alert alert-danger">{{$message}}</div>
            @endif
            <form role="form" method="post" action="">

                @if($username)
                    <div class="form-group">
                        <label for="exampleInputEmail1">{{trans('global.username')}}</label>
                        <input value="{{$username}}" type="text" class="form-control" placeholder="{{trans('global.username')}}" name="val[username]"/>
                    </div>
                @endif

                @if($email or empty($user->email_address))
                    <div class="form-group">
                        <label for="exampleInputPassword1">{{trans('global.email-address')}}</label>
                        <input value="{{$username}}" type="text" class="form-control" placeholder="{{trans('global.email-address')}}" name="val[email]"/>

                    </div>
                @endif

                <button type="submit" class="btn btn-success">{{trans('global.continue')}}</button>
            </form>

        </div>
    </div>
</div>