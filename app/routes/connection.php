<?php
Route::group(['before' => 'user-auth'], function() {

    Route::any('connection/remove-friend/{userid}/{touserid}', [
        'as' => 'connection-remove-friend',
        'uses' => 'App\\Controllers\\ConnectionController@removeFriend'
    ])->where([
            'userid' => '[0-9]+',
            'touserid' => '[0-9]+'
        ]);

    Route::any('friends/request', [
        'as' => 'friend-requests',
        'uses' => 'App\\Controllers\\ConnectionController@friendRequest'
    ]);

    Route::any('friend/reject/{id}', [
        'as' => 'connection-reject-friend',
        'uses' => 'App\\Controllers\\ConnectionController@rejectFriend'
    ])->where([
            'id' => '[0-9]+'
        ]);

    Route::any('friend/confirm/{id}', [
        'as' => 'connection-confirm-friend',
        'uses' => 'App\\Controllers\\ConnectionController@confirmFriend'
    ])->where([
            'id' => '[0-9]+'
        ]);

    Route::any('connection/dropdown', [
        'uses' => 'App\\Controllers\\ConnectionController@dropdown'
    ]);
});

Route::any('connection/add/{userid}/{touserid}/{way}', [
    'as' => 'connection-add',
    'before' => 'auth',
    'uses' => 'App\\Controllers\\ConnectionController@add'
])->where([
        'userid' => '[0-9]+',
        'touserid' => '[0-9]+',
        'way' => '[0-9]+'
    ]);

Route::any('connection/remove/{userid}/{touserid}/{way}', [
    'as' => 'connection-remove',
    'before' => 'auth',
    'uses' => 'App\\Controllers\\ConnectionController@remove'
])->where([
        'userid' => '[0-9]+',
        'touserid' => '[0-9]+',
        'way' => '[0-9]+'
    ]);

/**Profile routes**/
Route::any('{id}/connection/{type}', [
    'as' => 'profile-connection-list',
    'uses' => 'App\Controllers\ConnectionController@lists'
])->where(['id' => '[a-zA-Z0-9\_\-]+', 'type' => '[a-zA-Z0-9\_\-]+']);

