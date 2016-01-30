<?php
Route::group(['before' => 'user-auth'], function() {
   Route::any('like/{type}/{id}', [
       'uses' => 'App\\Controllers\\LikeController@like'
   ])->where(['type' => '[a-zA-Z0-9]+', 'id' => '[0-9]+']);

    Route::any('unlike/{type}/{id}', [
        'uses' => 'App\\Controllers\\LikeController@unlike'
    ])->where(['type' => '[a-zA-Z0-9]+', 'id' => '[0-9]+']);


});

Route::any('likes/{type}/{id}', [
    'as' => 'show-likes',
    'uses' => 'App\\Controllers\\LikeController@showLikes'
])->where(['type' => '[a-zA-Z0-9]+', 'id' => '[0-9]+']);