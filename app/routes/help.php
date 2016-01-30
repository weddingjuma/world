<?php

Route::any('helps', [
    'as' => 'helps',
    'uses' => 'App\\Controllers\\HelpController@index'
]);

Route::get('help/{slug}', [
    'as' => 'help-page',
    'uses' => 'App\Controllers\HelpController@page'
])->where('slug', '[a-zA-Z0-9\-\_]+');