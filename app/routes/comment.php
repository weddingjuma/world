<?php
Route::group(['prefix' => 'comment'], function() {
   Route::post('add', [
       'as' => 'comment-add',
       'uses' => 'App\\Controllers\\CommentController@add'
   ]);

    Route::any('load/more', [

        'uses' => 'App\\Controllers\\CommentController@loadMore'
    ]);

    Route::get('delete/{id}', [
        'as' => 'comment-delete',
        'uses' => 'App\\Controllers\\CommentController@delete'
    ])->where('id', '[0-9]+');

    Route::any('/edit/{id}', [
        'as' => 'comment-delete',
        'before' => 'auth',
        'uses' => 'App\\Controllers\\CommentController@edit'
    ])->where('id', '[0-9]+');

    Route::get('count/{type}/{id}', [
        'as' => 'comment-count',
        'uses' => 'App\\Controllers\\CommentController@count'
    ])->where(['type' => '[a-zA-Z0-9]+', 'id' => '[0-9]+']);
});
