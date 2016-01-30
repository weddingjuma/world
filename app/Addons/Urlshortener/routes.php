<?php
$prefix = \Config::get('shortener-prefix', '+');
$prefix = (empty($prefix)) ? '+' : $prefix;


Route::any('+{hash}', [
    'uses' => 'App\\Addons\\Urlshortener\\Controller\\ShortenedController@redirect'
])->where('hash', '[a-zA-Z0-9\_\-]+');

Route::any('-{hash}', [
    'uses' => 'App\\Addons\\Urlshortener\\Controller\\ShortenedController@redirect'
])->where('hash', '[a-zA-Z0-9\_\-]+');



Route::any('@{hash}', [
    'uses' => 'App\\Addons\\Urlshortener\\Controller\\ShortenedController@redirect'
])->where('hash', '[a-zA-Z0-9\_\-]+');