<?php

Route::any('install', [

    'uses' => 'App\\Install\\InstallController@index'
]);

