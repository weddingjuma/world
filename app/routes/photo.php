<?php
Route::any('photo/details', [
    'uses' => 'App\\Controllers\\PhotoController@details'
]);

Route::any('photo/album/create', [
    'uses' => 'App\\Controllers\\PhotoController@createAlbum'
]);

Route::any('photo/album/edit', [
    'uses' => 'App\\Controllers\\PhotoController@editAlbum'
]);

Route::any('photos/upload', [
    'uses' => 'App\\Controllers\\PhotoController@upload'
]);

Route::any('album/delete/{id}', [
    'uses' => 'App\\Controllers\\PhotoController@deleteAlbum',
    'as' => 'delete-album'
])->where('id', '[0-9]+');

Route::any('photo/delete/{id}', [
    'uses' => 'App\\Controllers\\PhotoController@deletePhoto',
    'as' => 'delete-photo'
])->where('id', '[0-9]+');


Route::any('{id}/photos', [
    'as' => 'profile-photos',
    'uses' => 'App\Controllers\PhotoController@profile'
])->where(['id' => '[a-zA-Z0-9\_\-]+']);

Route::any('{id}/album/{slug}', [
    'as' => 'profile-photos',
    'uses' => 'App\Controllers\PhotoController@albumPhotos'
])->where(['id' => '[a-zA-Z0-9\_\-]+', 'slug' => '[a-zA-Z0-9\_\-]+']);