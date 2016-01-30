<?php
Route::any('search', [
    'as' => 'search',
    'uses' => 'App\\Controllers\\SearchController@index'
]);

Route::any('search/dropdown', [
    'uses' => 'App\\Controllers\\SearchController@dropdown'
]);

/***Search hashtags**/
Route::any('search/hashtag', [
    'as' => 'search-hashtag',
    'uses' => 'App\\Controllers\\SearchController@hashtag'
]);

Route::any('search/posts', [
    'as' => 'search-posts',
    'uses' => 'App\\Controllers\\SearchController@posts'
]);

Route::any('search/pages', [
    'as' => 'search-pages',
    'uses' => 'App\\Controllers\\SearchController@pages'
]);

Route::any('search/games', [
    'as' => 'search-games',
    'uses' => 'App\\Controllers\\SearchController@games'
]);

Route::any('search/communities', [
    'as' => 'search-communities',
    'uses' => 'App\\Controllers\\SearchController@communities'
]);