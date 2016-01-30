<?php

Route::group(['prefix' => 'games', 'before' => 'user-auth'], function() {

    Route::get('delete/{id}', [
        'before' => 'user-auth',
        'as' => 'games-delete',
        'uses' => 'App\\Controllers\\GameController@delete'
    ]);

    Route::get('', [
        'before' => 'user-auth',
        'as' => 'games',
        'uses' => 'App\\Controllers\\GameController@index'
    ]);

    Route::get('mine', [
        'before' => 'user-auth',
        'as' => 'my-games',
        'uses' => 'App\\Controllers\\GameController@myGames'
    ]);

    Route::any('add', [
        'before' => 'user-auth',
        'as' => 'games-create',
        'uses' => 'App\\Controllers\\GameController@add'
    ]);

    Route::any('{id}/edit', [
        'as' => 'game-edit',
        'uses' => 'App\\Controllers\\GameProfileController@edit'
    ])->where('id', '[a-zA-Z0-9\_\-]+');

    Route::get('{id}', [
        'as' => 'game',
        'uses' => 'App\\Controllers\\GameProfileController@index'
    ])->where('id', '[a-zA-Z0-9\_\-]+');

    Route::any('save/photo', [
        'as' => 'games-change-photo',
        'before' => 'user-auth',
        'uses' => 'App\\Controllers\\GameController@changePhoto'
    ]);


});

