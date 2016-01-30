<div class="container">
    <div class="" id="static-content">
        <div class="static-note">
            <div class="note-content">{{trans('user.resend-activation-note')}}</div>
            <div class="arrow-down"></div>
        </div>

        <div class="main-content">
            @if($message)
                <div class="alert alert-info">{{$message}}</div>
            @endif
            <form role="form" method="post" action="">
                <div class="form-group">
                    <label for="exampleInputEmail1">{{trans('global.email-address')}}</label>
                    <input  type="text" name="email" value="" class="form-control" id="exampleInputEmail1" placeholder="{{trans('global.email-address')}}">

                </div>


                <button type="submit" class="btn btn-danger">{{trans('user.account-resend-activation')}}</button>
            </form>

        </div>
    </div>
</div>