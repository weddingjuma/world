<?php

\Menu::add('admincp-menu', 'custom-widgets', [
    'link' => '',
    'name' => 'Manage Widgets',
]);

\Menu::add('sub-menu-custom-widgets', 'lists', [
    'link' => \URL::to('admincp/custom/widgets/'),
    'name' => 'Lists',
]);

\Menu::add('sub-menu-custom-widgets', 'add-new', [
    'link' => \URL::to('admincp/custom/widgets/add'),
    'name' => 'Add New Widget',
]);


$widgets = app('App\\Addons\\Customwidget\\Classes\\CustomWidgetRepository')->getList();

foreach($widgets as $widget) {
    if ($widget->page == 'all')  {
        app('widget')->add('customwidget::display', [
            'user-home',
            'user-search',
            'user-discover',
            'notifications',
            'user-community'
        ], ['widget' =>  $widget]);
    } else {
        app('widget')->add('customwidget::display', [
            $widget->page,

        ], ['widget' =>  $widget]);
    }
}
