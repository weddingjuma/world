<?php

Route::any('install', [
    'uses' => 'App\\Install\\InstallController@index'
]);

Route::any('install/db-info', [
    'as' => 'install-db-info',
    'uses' => 'App\\Install\\InstallController@dbInfo'
]);

Route::any('install/db', [
    'as' => 'install-db',
    'uses' => 'App\\Install\\InstallController@db'
]);

Route::any('install/site', [
    'as' => 'install-site-info',
    'uses' => 'App\\Install\\InstallController@site'
]);