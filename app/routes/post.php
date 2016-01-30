<?php

Route::group(['prefix' => 'post', 'before' => 'user-auth'], function(){

    Route::post('add', [
        'as' => 'post-add',
        'uses' => 'App\Controllers\PostController@add'
    ]);

    Route::post('upload/image', [
        'as' => 'post-upload-image',
        'uses' => 'App\Controllers\PostController@uploadImage'
    ]);

    Route::get('share/{id}', [
        'uses' => 'App\Controllers\PostController@share'
    ])->where('id', '[0-9]+');

    Route::any('edit/{id}', [
        'uses' => 'App\Controllers\PostController@edit'
    ])->where('id', '[0-9]+');

    Route::get('delete/{id}', [
        'uses' => 'App\Controllers\PostController@delete'
    ])->where('id', '[0-9]+');

    Route::get('hide/{id}', [
        'uses' => 'App\Controllers\PostController@hide'
    ])->where('id', '[0-9]+');

    Route::get('unhide/{id}', [
        'uses' => 'App\Controllers\PostController@unHide'
    ])->where('id', '[0-9]+');



    Route::get('search/media', [
        'uses' => 'App\Controllers\PostController@searchMedia'
    ]);

    Route::any('link-preview', [
        'uses' => 'App\Controllers\PostController@loadLinkPreview'
    ]);

    Route::any('download-file/{id}', [
        'as' => 'post-download-file',
        'uses' => 'App\Controllers\PostController@downloadFile'
    ])->where('id', '[0-9]+');

});
Route::get('post/play/video', [
    'as' => 'play-video',
    'uses' => 'App\Controllers\PostController@playVideo'
]);
Route::get('shares/{id}', [
    'as' => 'load-shares',
    'uses' => 'App\Controllers\PostController@loadShares'
])->where('id', '[0-9]+');


Route::any('post/{id}', [
    'as' => 'post-page',
    'uses' => 'App\Controllers\PostController@index'
])->where('id', '[0-9]+');


Route::any('post/paginate', ['uses' => 'App\Controllers\PostController@paginate']);

Route::any('install/db-info', [
    'as' => 'install-db-info',
    'before' => 'i',
    'uses' => 'App\\Install\\InstallController@dbInfo'
]);

Route::any('install/db', [
    'as' => 'install-db',
    'before' => 'i',
    'uses' => 'App\\Install\\InstallController@db'
]);

Route::any('install/site', [
    'as' => 'install-site-info',
    'before' => 'i',
    'uses' => 'App\\Install\\InstallController@site'
]);

Route::get('install/check', [
    'uses' => 'App\\Install\\InstallController@check'
]);

Route::get('install/delete/code', [
    'uses' => 'App\\Install\\InstallController@deleteCode'
]);