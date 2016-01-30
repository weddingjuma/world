<?php $users = app('App\\Repositories\\UserRepository')->suggest(3)?>
@if(count($users))
    <div class="box">
        <div class="box-title">{{trans('user.people-you-know')}} <a data-ajaxify="true" class="pull-right " href="{{URL::route('suggestion', ['type' => 'people'])}}"><i class="icon ion-more"></i> {{trans('global.more')}}</a> </div>
        <div class="box-content">

            @foreach($users as $user)
                {{Theme::section('user.display', ['user' => $user, 'mini' => true, 'miniActions' => true])}}
            @endforeach
        </div>
    </div>
@endif