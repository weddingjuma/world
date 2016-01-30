<?php
Route::group(['prefix' => 'messages', 'before' => 'user-auth'], function() {

    Route::post('send', [
        'uses' => 'App\\Controllers\\MessageController@send'
    ]);

    Route::any('', [
        'uses' => 'App\\Controllers\\MessageController@index',
        'as' => 'messages'
    ]);

    Route::any('more', [
        'uses' => 'App\\Controllers\\MessageController@more',

    ]);

    Route::any('delete', [
        'uses' => 'App\\Controllers\\MessageController@delete',

    ]);



    Route::any('dropdown', [
        'uses' => 'App\\Controllers\\MessageController@dropdown',

    ]);
});

Route::any('messages/online', [
    'uses' => 'App\\Controllers\\MessageController@online',

]);

Route::any('set/online/status', [
    'uses' => 'App\\Controllers\\MessageController@setOnlineStatus',

]);