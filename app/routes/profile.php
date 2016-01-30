<?php


Route::group(['before' => 'user-auth', 'prefix' => 'profile/'], function() {

    Route::any('upload/cover', [
        'uses' => 'App\Controllers\ProfileController@uploadCover'
    ]);

    Route::any('crop/cover', [
        'uses' => 'App\Controllers\ProfileController@cropCover'
    ]);

    Route::any('remove/cover', [
        'uses' => 'App\Controllers\ProfileController@removeCover'
    ]);
});

Route::any('{id}', [
    'as' => 'profile',
    'uses' => 'App\Controllers\ProfileController@index'
])->where('id', '[a-zA-Z0-9\_\-]+');
