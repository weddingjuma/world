<div class="box">
    <div class="box-title">{{trans('user.deactivate-account')}}</div>
    <div class="box-content">
        <h5><strong>{{trans('user.sure-to-deactivate')}}</strong></h5>
        <div class="alert alert-warning">
            {{trans('user.deactivating-info')}}
        </div>

        <h5>{{trans('user.friend-no-longer-see-you', ['count' => Auth::user()->countFriends()])}}  </h5>

        @foreach(Auth::user()->friends(5) as $friend)
            <?php $theFriend = $friend->present()->getFriend(\Auth::user()->id)?>
            <div class="media">
                <div class="media-object pull-left">
                    <a href="{{$theFriend->present()->url()}}"><img width="40" height="40" src="{{$theFriend->present()->getAvatar(50)}}"/> </a></a>
                </div>
                <div class="media-body">
                    <h6 class="media-heading">{{$theFriend->fullname}} {{trans('user.will-miss-you')}}</h6>
                </div>
            </div>


        @endforeach

        <form style="margin-top: 30px" action="" method="post">
            <input type="checkbox" name="val[permanent]"/> <label>{{trans('user.delete-account-permanently')}}</label>
            <div class="alert alert-danger">
                {{trans('user.delete-account-permanently-note')}}
            </div>
            <input type="hidden" name="val[userid]" value="{{Auth::user()->id}}"/>

            <div class="divider"></div>

                    <button type="submit" class="btn btn-sm btn-danger">{{trans('user.deactivate-account')}}</button>
                    <a href="{{URL::route('user-account')}}"   class="btn btn-success btn-sm pull-right">{{trans('global.cancel')}}</a>

        </form>
    </div>
</div>