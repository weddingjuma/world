<?php
Route::get('api', [
    'uses' => 'App\\Controllers\\ApiController@get'
]);