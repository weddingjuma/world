<?php
Route::get('about', [
    'as' => 'about-us',
    'uses' => 'App\\Controllers\\AdditionalPageController@about'
]);

Route::get('terms', [
    'as' => 'terms-and-condition',
    'uses' => 'App\\Controllers\\AdditionalPageController@terms'
]);

Route::get('disclaimer', [
    'as' => 'disclaimer',
    'uses' => 'App\\Controllers\\AdditionalPageController@disclaimer'
]);

Route::get('privacy', [
    'as' => 'privacy',
    'uses' => 'App\\Controllers\\AdditionalPageController@privacy'
]);

Route::get('developers', [
    'as' => 'developers',
    'uses' => 'App\\Controllers\\AdditionalPageController@developers'
]);