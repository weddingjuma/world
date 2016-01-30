<div class="container">
    <div class="" id="static-content">
        <div class="static-note">
            <div class="note-content">{{trans('user.retrieve-password-title')}}</div>
            <div class="arrow-down"></div>
        </div>

        <div class="main-content">
            <form role="form" method="post" action="">
                @if(!empty($message))
                    <div class="alert alert-danger">{{$message}}</div>
                @endif
              <div class="form-group">
                <label >{{trans('user.new-password')}}</label>
                <input  type="password" name="val[password]"  value="" class="form-control"  placeholder="{{trans('user.new-password')}}">

              </div>


              <div class="form-group">
                <label >{{trans('user.confirm-password')}}</label>
                <input  type="password" name="val[password_confirmation]"  value="" class="form-control"  placeholder="{{trans('user.confirm-password')}}">

              </div>

              <button type="submit" class="btn btn-danger">{{trans('user.change-password')}}</button>
            </form>

        </div>
    </div>
</div>