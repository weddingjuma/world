<div class="container">
    <div class="" id="static-content">
        <div class="static-note">
            <div class="note-content"><b>{{trans('user.forgot-password')}}</b> >> {{trans('user.forgot-pass-note')}}</div>
            <div class="arrow-down"></div>
        </div>

        <div class="main-content">
            <form role="form" method="post" action="">
                @if(!empty($message))
                    <div class="alert alert-danger">{{$message}}</div>
                @endif
              <div class="form-group">
                <label for="exampleInputEmail1">{{trans('global.email-address')}}</label>
                <input  type="text" name="email"  value="" class="form-control" id="exampleInputEmail1" placeholder="{{trans('global.email-address')}}">

              </div>


              <button type="submit" class="btn btn-danger">{{trans('user.retrieve-password')}}</button>
            </form>

        </div>
    </div>
</div>