<?php

\Menu::add('admincp-menu', 'online-members', [
    'link' => URL::to('admincp/online/members'),
    'name' => 'Online Members',
]);

if (Config::get('onlinemember-seen-by-users', 1)) {
    app('menu')->add('site-menu', 'onlines', [
        'name' => trans('onlinemembers::global.online-members'),
        'link' => \URL::to('onlines'),
        'ajaxify' => true,
        'icon' => '<i class="icon ion-android-social"></i>'
    ]);
}

