<?php

\Menu::add('admincp-menu', 'custom-page', [
    'link' => '',
    'name' => 'Custom Pages',
]);

\Menu::add('sub-menu-custom-page', 'lists', [
    'link' => \URL::to('admincp/custom/pages/'),
    'name' => 'Lists',
]);

\Menu::add('sub-menu-custom-page', 'add', [
    'link' => \URL::to('admincp/custom/pages/add'),
    'name' => 'Add New Page',
]);

$customRepository = app('App\\Addons\\Custompage\\Classes\\CustomPageRepository');

foreach($customRepository->getList(null, true) as $page) {
    app('menu')->add('site-menu', $page->slug, [
        'name' => $page->title,
        'link' => \URL::to('_'.$page->slug),
        'ajaxify' => true,
        'icon' => '<i class="icon ion-clipboard"></i>'
    ]);
}

if (\Config::get('enable-recent-pages')) {
    app('widget')->add('custompage::side', [
        'user-home',
        'user-search',
        'user-discover',
        'notifications',
        'user-community',
        'user-pages'
    ], ['all' => true]);
}
