<?php
Route::any('report/{type}', [
    'as' => 'report',
    'before' => 'user-auth',
    'uses' => 'App\Controllers\ReportController@index'
])->where(['type' => '[a-zA-Z0-9]+']);