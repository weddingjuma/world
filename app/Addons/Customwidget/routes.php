<?php
Route::group(['prefix' => 'admincp/custom/', 'before' => 'admincp-auth'], function() {

    Route::any('widgets', [
        'as' => 'admincp-custom-widgets',
        'uses' => 'App\\Addons\\Customwidget\\Controller\\AdmincpController@index'
    ]);

    Route::any('widgets/add', [
        'as' => 'admincp-custom-widgets-add',
        'uses' => 'App\\Addons\\Customwidget\\Controller\\AdmincpController@add'
    ]);

    Route::any('widgets/edit/{id}', [
        'as' => 'admincp-custom-widgets-edit',
        'uses' => 'App\\Addons\\Customwidget\\Controller\\AdmincpController@edit'
    ])->where('id', '[0-9]+');

    Route::any('widgets/delete/{id}', [
        'as' => 'admincp-custom-widgets-delete',
        'uses' => 'App\\Addons\\Customwidget\\Controller\\AdmincpController@delete'
    ])->where('id', '[0-9]+');

});

if (Config::get('onlinemember-seen-by-users', 1)) {
    Route::any('onlines', [
        'as' => 'online-members',
        'uses' => 'App\\Addons\\Onlinemembers\\Controller\\OnlineController@index'
    ]);
}