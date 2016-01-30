<?php

Route::any('invite/{type}/{typeId}/{userid}', [
    'as' => 'invite-member',
    'uses' => 'App\\Controllers\\InviteController@invite',
    'before' => 'user-auth'
]);


Route::any('invite', [
    'as' => 'invite',
    'uses' => 'App\\Controllers\\InviteController@index',
    'before' => 'user-auth'
]);