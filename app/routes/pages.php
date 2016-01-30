<?php

/**
*
*@author: Tiamiyu waliu kola
*@website : www.crea8social.com
*/
Route::any('pages', [
    'as' => 'pages',
    'before' => 'user-auth',
    'uses' => 'App\\Controllers\\PageController@index'
]);

Route::any('pages/mine', [
    'as' => 'my-pages',
    'before' => 'user-auth',
    'uses' => 'App\\Controllers\\PageController@mine'
]);


Route::any('pages/save/photo', [
    'as' => 'pages-change-photo',
    'before' => 'user-auth',
    'uses' => 'App\\Controllers\\PageController@changePhoto'
]);

Route::any('page/upload/cover', [
    'before' => 'user-auth',
    'uses' => 'App\\Controllers\\PageController@uploadCover'
]);

Route::any('page/crop/cover/{id}', [
    'before' => 'user-auth',
    'uses' => 'App\\Controllers\\PageController@cropCover'
])->where('id', '[0-9]+');

Route::any('page/remove/cover', [
    'before' => 'user-auth',
    'uses' => 'App\\Controllers\\PageController@removeCover'
]);

Route::any('page/delete/{id}', [
    'before' => 'user-auth',
    'as' => 'delete-page',
    'uses' => 'App\\Controllers\\PageController@delete'
])->where('id', '[0-9]+');


Route::any('pages/create', [
    'as' => 'pages-create',
    'before' => 'user-auth',
    'uses' => 'App\\Controllers\\PageController@create'
]);

Route::any('pages/suggest', [
    'before' => 'user-auth',
    'uses' => 'App\\Controllers\\PageController@suggestAdmin'
]);

Route::post('pages/add/admin', [
    'before' => 'user-auth',
    'uses' => 'App\\Controllers\\PageController@addAdmin'
]);

Route::post('pages/update/admin', [
    'before' => 'user-auth',
    'uses' => 'App\\Controllers\\PageController@updateAdmin'
]);

Route::any('pages/remove/admin', [
    'before' => 'user-auth',
    'uses' => 'App\\Controllers\\PageController@removeAdmin'
]);


Route::any('pages/delete/{id}', [
    'as' => 'pages-delete',
    'before' => 'user-auth',
    'uses' => 'App\\Controllers\\PageController@delete'
])->where('id', '[0-9]+');

Route::any('pages/load/more/invitees', [
    'uses' => 'App\\Controllers\\PageController@loadMoreInvitee'
]);

Route::any('pages/search/for/invitees', [
    'uses' => 'App\\Controllers\\PageController@searchInvitee'
]);


Route::any('page/{slug}', [
    'uses' => 'App\\Controllers\\PageProfileController@index',
    'as' => 'page',
])->where('slug', '[a-zA-Z0-9\-\_]+');

Route::any('page/{slug}/photos', [
    'uses' => 'App\\Controllers\\PageProfileController@photos',
    'as' => 'page-photos',
])->where('slug', '[a-zA-Z0-9\-\_]+');

Route::any('pages/add/photos', [
    'uses' => 'App\\Controllers\\PageProfileController@addPhotos',
]);


Route::any('page/{slug}/edit', [
    'uses' => 'App\\Controllers\\PageProfileController@edit',
    'as' => 'page-edit',
    'before' => 'user-auth'
])->where('slug', '[a-zA-Z0-9\-\_]+');

Route::any('page/{slug}/roles', [
    'uses' => 'App\\Controllers\\PageProfileController@manageAdmins',
    'as' => 'page-edit',
    'before' => 'user-auth'
])->where('slug', '[a-zA-Z0-9\-\_]+');


Route::any('page/{slug}/design', [
    'uses' => 'App\\Controllers\\PageProfileController@design',
    'as' => 'page-edit',
    'before' => 'user-auth'
])->where('slug', '[a-zA-Z0-9\-\_]+');
