<div class="box" style="margin-top: 20px">
    <div class="box-title">{{trans('user.cant-access-profile', ['name' => $profileUser->fullname])}}</div>
    <div class="box-content">
        <div class="media" style="margin: 20px 20px;color: #808080">
            <div class="media-object pull-left">
                <i class="icon ion-locked" style="font-size: 150px"></i>
            </div>
            <div class="media-body" style="margin-top: 50px;font-size: 18px">
                {{$profileUser->present()->fullName()}} {{trans('user.access-note')}}
            </div>
        </div>
    </div>
</div>