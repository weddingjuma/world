<?php

Route::group(['prefix' => 'autotranslator'], function() {
    Route::post('translate', [
        'uses' => 'App\\Addons\\Autotranslator\\Controller\\AutotranslatorController@translate'
    ]);
});