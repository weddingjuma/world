<?php
Route::group(['prefix' => 'admincp/', 'before' => 'admincp-auth'], function() {

    Route::any('online/members', [
        'as' => 'admincp-online-members',
        'uses' => 'App\\Addons\\Onlinemembers\\Controller\\AdmincpController@index'
    ]);



});

if (Config::get('onlinemember-seen-by-users', 1)) {
    Route::any('onlines', [
        'as' => 'online-members',
        'uses' => 'App\\Addons\\Onlinemembers\\Controller\\OnlineController@index'
    ]);
}