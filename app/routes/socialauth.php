<?php
Route::any('auth/facebook', [
    'as' => 'facebook-auth',
    'uses' => 'App\\Controllers\\SocialauthController@facebook'
]);

Route::any('auth/twitter', [
    'as' => 'twitter-auth',
    'uses' => 'App\\Controllers\\SocialauthController@twitter'
]);

Route::any('auth/twitter/data', [
    'as' => 'twitter-auth-data',
    'uses' => 'App\\Controllers\\SocialauthController@twitterData'
]);

Route::any('auth/vk', [
    'as' => 'vk-auth',
    'uses' => 'App\\Controllers\\SocialauthController@vk'
]);

Route::any('auth/vk/data', [
    'as' => 'vk-auth-data',
    'uses' => 'App\\Controllers\\SocialauthController@vkData'
]);
Route::any('auth/complete/{username}', [
    'as' => 'auth-complete',
    'uses' => 'App\\Controllers\\SocialauthController@complete'
])->where('username', '[a-zA-Z0-9\-\_]+');

Route::any('auth/google', [
    'as' => 'google-auth',
    'uses' => 'App\\Controllers\\SocialauthController@google'
]);
