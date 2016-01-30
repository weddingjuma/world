<div class="container">
    <div class="" id="static-content">
        <div class="static-note">
            <div class="note-content">{{trans('user.thanks-for-signup')}}</div>
            <div class="arrow-down"></div>
        </div>

        <div class="main-content">
            <form role="form" method="post" action="">
              <div class="form-group">
                <label for="exampleInputEmail1">{{trans('global.email-address')}}</label>
                <input disabled="disabled" type="text"  value="{{$email}}" class="form-control" id="exampleInputEmail1" placeholder="{{trans('global.email-address')}}">
                <input type="hidden" name="val[email]" value="{{$email}}" class="form-control" >
              </div>
              <div class="form-group">
                <label for="exampleInputPassword1">{{trans('user.activation-code')}}</label>
                <input type="text" name="val[code]" value="{{$code}}" class="form-control" id="exampleInputPassword1" placeholder="{{trans('user.activation-code')}}">
              </div>

              <button type="submit" class="btn btn-danger">{{trans('user.activate-now')}}</button>
            </form>

        </div>
    </div>
</div>