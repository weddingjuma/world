<div id="login-container">


    @if(!empty($message))
        <div id="login-title">{{$message}}</div>
    @endif
    <form class="form-horizontal" action="" method="post">

            <input id="username" type="text" name="val[username]" placeholder="{{trans('global.your-username')}}"/>

            <input id="password" type="password" name="val[password]" placeholder="{{trans('global.your-password')}}"/><br/><br/>

        <div id="login-footer">
            <span id="forgot-password-link">
                <i class="icon ion-help-circled"></i> <a href="">{{trans('user.forgot-password')}}</a>
            </span>
            <input class="btn btn-blue no-radius pull-right" type="submit" value="Login"/>
        </div>
    </form>


</div>




