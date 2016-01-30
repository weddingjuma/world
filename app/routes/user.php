<?php
Route::any('login', ['as' => 'login', 'uses' => 'App\Controllers\LoginController@login']);
Route::any('forgot-password', ['as' => 'forgot-password', 'uses' => 'App\Controllers\LoginController@forgotPassword']);
Route::any('retrieve-password', ['as' => 'retrieve-password', 'uses' => 'App\Controllers\LoginController@retrievePassword']);

Route::group(['before' => 'user-auth'], function() {

    /**
     * User home
     */
    Route::any('home', [
        'as' => 'user-home',
        'uses' => 'App\Controllers\UserHomeController@index'
    ]);

    Route::any('user/update/post-privacy', [
        'uses' => 'App\\Controllers\\AccountController@updatePostPrivacy'
    ]);

    /**user settings**/
    Route::any('account', [
        'as' => 'user-account',
        'uses' => 'App\Controllers\AccountController@index'
    ]);

    Route::any('account/deactivate', [
        'as' => 'user-deactivate',
        'uses' => 'App\Controllers\AccountController@deactivate'
    ]);

    Route::any('account/privacy', [
        'as' => 'user-account-privacy',
        'uses' => 'App\Controllers\AccountController@privacy'
    ]);

    Route::any('account/design', [
        'as' => 'user-design',
        'uses' => 'App\Controllers\AccountController@designPage'
    ]);

    Route::any('account/design/bg', [
        'uses' => 'App\Controllers\AccountController@uploadBg'
    ]);

    /**block user**/
    Route::any('block/user/{id}', [
        'uses' => 'App\Controllers\AccountController@blockUser'
    ])->where('id', '[0-9]+');

    Route::any('unblock/{id}', [
        'uses' => 'App\Controllers\AccountController@unBlock'
    ])->where('id', '[0-9]+');

    Route::any('account/blocked', [
        'as' => 'block-users',
        'uses' => 'App\Controllers\AccountController@listBlockUsers'
    ]);

    Route::any('account/profile', [
        'as' => 'edit-profile',
        'uses' => 'App\Controllers\AccountController@profile'
    ]);

    Route::any('account/notifications', [
        'as' => 'notification-privacy',
        'uses' => 'App\Controllers\AccountController@notifications'
    ]);

    Route::any('user/tag/suggestion', [
        'uses' => 'App\Controllers\UserHomeController@tagSuggestion'
    ]);

    Route::any('user/tag/username', [
        'uses' => 'App\Controllers\UserHomeController@tagUsername'
    ]);

    Route::any('user/tag/hashtag', [
        'uses' => 'App\Controllers\UserHomeController@hashTag'
    ]);


    Route::any('suggestion/{type}', [
        'as' => 'suggestion',
        'uses' => 'App\Controllers\UserHomeController@suggestion'
    ])->where('type', '[a-zA-Z0-9\_\-]+');

    Route::any('account/delete', [
        'as' => 'delete-account',
        'uses' => 'App\Controllers\AccountController@delete'
    ]);

});

Route::any('user/popover/{id}', [
    'as' => 'load-user-popover',
    'uses' => 'App\Controllers\UserHomeController@popover'
])->where('id', '[0-9]+');


/**Signup routes**/
Route::any('signup', [
    'as' => 'signup',
    'uses' => 'App\Controllers\SignupController@index'
]);

/**User Activation**/
Route::any('account/activate', [
    'as' => 'user-activation',
    'uses' => 'App\Controllers\SignupController@activate'
]);

Route::any('account/resend/activation', [
    'as' => 'user-resend-activation',
    'uses' => 'App\Controllers\SignupController@resendActivate'
]);


/**Getstarted **/
Route::any('getstarted', [
    'as' => 'user.getstarted',
    'before' => 'auth',
    'uses' => 'App\Controllers\UserGetstartedController@index'
]);

Route::any('getstarted/finish', [
    'as' => 'getstarted.finish',
    'before' => 'auth',
    'uses' => 'App\Controllers\UserGetstartedController@finish'
]);

Route::any('getstarted/save/photo', [
    'uses' => 'App\Controllers\UserGetstartedController@savePhoto'
]);

Route::any('getstarted/save/info', [
    'uses' => 'App\Controllers\UserGetstartedController@saveInfo'
]);

Route::any('getstarted/members', [
    'uses' => 'App\Controllers\UserGetstartedController@getMembers'
]);