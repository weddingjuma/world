<?php
Route::group(['prefix' => 'discover', 'before' => 'user-auth'], function() {
   Route::any('post', [
       'as' => 'discover-post',
       'uses' => 'App\\Controllers\\DiscoverController@post'
   ]);

    Route::any('mention', [
        'as' => 'discover-mention',
        'uses' => 'App\\Controllers\\DiscoverController@mention'
    ]);

    Route::any('communities', [
        'as' => 'discover-communities',
        'uses' => 'App\\Controllers\\DiscoverController@communities'
    ]);
});