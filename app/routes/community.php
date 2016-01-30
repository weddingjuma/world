<?php
Route::group(['prefix' => 'community/', 'before' => 'user-auth'], function() {

    Route::any('create', [
        'uses' => 'App\\Controllers\\CommunityController@create',
        'as'=> 'community-create',
    ]);


});

Route::any('community/{slug}', [
    'uses' => 'App\\Controllers\\CommunityPageController@index',
    'as' => 'community-page',
])->where('slug', '[a-zA-Z0-9\-\_]+');

Route::any('community/{slug}/edit', [
    'uses' => 'App\\Controllers\\CommunityPageController@edit',
    'as' => 'community-edit',
])->where('slug', '[a-zA-Z0-9\-\_]+');

Route::any('community/{slug}/design', [
    'uses' => 'App\\Controllers\\CommunityPageController@design',
    'as' => 'community-design',
])->where('slug', '[a-zA-Z0-9\-\_]+');

Route::any('community/{slug}/about', [
    'uses' => 'App\\Controllers\\CommunityPageController@about',
    'as' => 'community-about',
])->where('slug', '[a-zA-Z0-9\-\_]+');

Route::any('community/join/{id}', [
    'uses' => 'App\\Controllers\\CommunityPageController@join',
    'before' => 'user-auth'
])->where('id', '[a-zA-Z0-9\-\_]+');

Route::any('community/category/delete/{id}', [
    'uses' => 'App\\Controllers\\CommunityPageController@deleteCategory',
    'as' => 'community-category-delete',
    'before' => 'user-auth'
])->where('id', '[0-9]+');

Route::any('community/delete/{id}', [
    'uses' => 'App\\Controllers\\CommunityController@delete',
    'as' => 'community-delete',
    'before' => 'user-auth'
])->where('id', '[a-zA-Z0-9\-\_]+');


Route::any('community/category/add', [
    'uses' => 'App\\Controllers\\CommunityPageController@addCategory',
    'as' => 'community-category-add',
]);

Route::any('community/{slug}/category/{category}', [
    'uses' => 'App\\Controllers\\CommunityPageController@category',
    'as' => 'community-category-post',
])->where(['slug' => '[a-zA-Z0-9\-\_]+', 'category' => '[a-zA-Z\_\-0-9]+']);

Route::any('community/{slug}/invite', [
    'uses' => 'App\\Controllers\\CommunityPageController@invite',
    'as' => 'community-category-post',
])->where(['slug' => '[a-zA-Z0-9\-\_]+']);

Route::any('community/{slug}/members', [
    'uses' => 'App\\Controllers\\CommunityPageController@members',
    'as' => 'community-category-post',
])->where(['slug' => '[a-zA-Z0-9\-\_]+']);


Route::any('communities', [
    'uses' => 'App\\Controllers\\CommunityController@index',
    'as'=> 'communities',
    'before' => 'user-auth'
]);
Route::any('communities/joined', [
    'uses' => 'App\\Controllers\\CommunityController@joined',
    'as'=> 'communities-joined',
    'before' => 'user-auth'
]);

Route::any('community/leave/{id}', [
    'uses' => 'App\\Controllers\\CommunityController@leave',
    'as'=> 'leave-community',
    'before' => 'user-auth'
])->where('id', '[0-9]+');

Route::any('community/assign-moderator/{id}/{userid}', [
    'uses' => 'App\\Controllers\\CommunityController@assignModerator',
    'as'=> 'assign-moderator',
    'before' => 'user-auth'
])->where(['id' => '[0-9]+', 'userid' => '[0-9]+']);

Route::any('community/remove-moderator/{id}/{userid}', [
    'uses' => 'App\\Controllers\\CommunityController@removeModerator',
    'as'=> 'remove-moderator',
    'before' => 'user-auth'
])->where(['id' => '[0-9]+', 'userid' => '[0-9]+']);


Route::any('community/upload/cover', [
    'uses' => 'App\\Controllers\\CommunityPageController@uploadCover',
    'before' => 'user-auth'
]);

Route::any('community/crop/cover', [
    'uses' => 'App\\Controllers\\CommunityPageController@cropCover',
    'before' => 'user-auth'
]);