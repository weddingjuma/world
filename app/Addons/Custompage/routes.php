<?php

Route::group(['prefix' => 'admincp/custom/pages/', 'before' => 'admincp-auth'], function() {

    Route::any('', [
        'as' => 'admincp-custom-pages',
        'uses' => 'App\\Addons\\Custompage\\Controllers\\AdmincpController@index'
    ]);

    Route::any('add', [
        'as' => 'admincp-custom-pages-add',
        'uses' => 'App\\Addons\\Custompage\\Controllers\\AdmincpController@add'
    ]);

    Route::any('edit/{slug}', [
        'as' => 'admincp-custom-pages-edit',
        'uses' => 'App\\Addons\\Custompage\\Controllers\\AdmincpController@edit'
    ])->where('slug', '[a-zA-Z0-9\_\-]+');

    Route::any('delete/{slug}', [
        'as' => 'admincp-custom-pages-delete',
        'uses' => 'App\\Addons\\Custompage\\Controllers\\AdmincpController@delete'
    ])->where('slug', '[a-zA-Z0-9\_\-]+');

});

Route::any('_{slug}', [
    'as' => 'custom-page',
    'uses' => 'App\\Addons\\Custompage\\Controllers\\CustomPageController@index'
])->where('slug', '[a-zA-Z0-9\_\-]+');