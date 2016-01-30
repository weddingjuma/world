<?php
Route::group(['before' => 'user-auth', 'prefix' => 'notification'], function() {
   Route::get('dropdown', [
       'uses' => 'App\\Controllers\\NotificationController@dropdown'
   ]);

    Route::any('delete/{id}', [
        'as' => 'notifications-delete',
        'uses' => 'App\\Controllers\\NotificationController@delete'
    ])->where('id', '[0-9]+');

    Route::any('', [
        'as' => 'notifications',
        'uses' => 'App\\Controllers\\NotificationController@index'
    ]);

    Route::get('markall', [
        'as' => 'notification-mark-all',
        'uses' => 'App\\Controllers\\NotificationController@markAll'
    ]);

    Route::get('clearall', [
        'as' => 'notification-clear-all',
        'uses' => 'App\\Controllers\\NotificationController@clearAll'
    ]);

    Route::get('receiver/remove/{userid}/{type}/{typeid}', [
        'uses' => 'App\\Controllers\\NotificationController@removeReceiver'
    ])->where(['userid' => '[0-9]+', 'type' => '[0-9a-zA-Z]+', 'typeid' => '[0-9]+']);


    Route::get('receiver/add/{userid}/{type}/{typeid}', [
        'uses' => 'App\\Controllers\\NotificationController@addReceiver'
    ])->where(['userid' => '[0-9]+', 'type' => '[0-9a-zA-Z]+', 'typeid' => '[0-9]+']);

});

Route::any('user/update', [
    'uses' => 'App\\Controllers\\UserUpdateController@process',
]);