<?php

Route::group(['prefix' => 'admincp/', 'before' => 'admincp-auth'], function() {

    Route::get('', [
        'as' => 'admincp',
        'uses' => 'App\Controllers\Admincp\AdmincpController@dashboard'
    ]);

    Route::get('configurations/{type}', [
        'as' => 'admincp-configuration',
        'uses' => 'App\Controllers\Admincp\ConfigurationController@index'
    ])->where('type', '[a-zA-Z0-9\-\_]+');

    Route::post('save/configurations', [
        'as' => 'admincp-save-configuration',
        'uses' => 'App\Controllers\Admincp\ConfigurationController@save'
    ]);

    Route::any('update/configurations', [
        'as' => 'admincp-update-configuration',
        'uses' => 'App\Controllers\Admincp\ConfigurationController@update'
    ]);

    /***addons***/
    Route::any('addons', [
        'as' => 'admincp-addon',
        'uses' => 'App\Controllers\Admincp\AddonController@index'
    ]);

    Route::any('addons/disable/{slug}', [
        'as' => 'admincp-disable-addon',
        'uses' => 'App\Controllers\Admincp\AddonController@disable'
    ])->where('slug', '[a-zA-Z0-9]+');

    Route::any('addons/enable/{slug}', [
        'as' => 'admincp-enable-addon',
        'uses' => 'App\Controllers\Admincp\AddonController@enable'
    ])->where('slug', '[a-zA-Z0-9]+');

    Route::any('addons/update/{slug}', [
        'as' => 'admincp-update-addon',
        'uses' => 'App\Controllers\Admincp\AddonController@update'
    ])->where('slug', '[a-zA-Z0-9]+');

    Route::any('addons/{slug}/configurations', [
        'as' => 'admincp-configuration-addon',
        'uses' => 'App\Controllers\Admincp\AddonController@configurations'
    ])->where('slug', '[a-zA-Z0-9]+');

    /***Theme management**/
    Route::any('theme', [
        'as' => 'admincp-theme',
        'uses' => 'App\Controllers\Admincp\ThemeController@index'
    ]);

    Route::any('theme/activate', [
        'as' => 'admincp-theme-activate',
        'uses' => 'App\Controllers\Admincp\ThemeController@setActive'
    ]);

    Route::any('theme/configurations', [
        'as' => 'admincp-theme-configurations',
        'uses' => 'App\Controllers\Admincp\ThemeController@configurations'
    ]);


    /**
     * Languages
     */
    Route::get('languages', [
        'as' => 'admincp-languages',
        'uses' => 'App\Controllers\Admincp\LanguageController@index'
    ]);

    Route::any('languages/add', [
        'as' => 'admincp-languages-add',
        'uses' => 'App\Controllers\Admincp\LanguageController@add'
    ]);

    Route::any('languages/activate/{var}', [
        'as' => 'admincp-languages-activate',
        'uses' => 'App\Controllers\Admincp\LanguageController@activate'
    ])->where('var', '[a-zA-Z\_\-]+');

    Route::any('languages/delete/{var}', [
        'as' => 'admincp-languages-delete',
        'uses' => 'App\Controllers\Admincp\LanguageController@delete'
    ])->where('var', '[a-zA-Z0-9\_\-]+');


    /***Database ***/
    Route::any('database/update', [
        'as' => 'admincp-database-update',
        'uses' => 'App\Controllers\Admincp\DatabaseController@update'
    ]);

    /**communities routes**/
    Route::any('communities', [
        'as' => 'admincp-communities',
        'uses' => 'App\Controllers\Admincp\CommunityController@index'
    ]);

    Route::any('community/edit/{id}', [
        'as' => 'admincp-community-edit',
        'uses' => 'App\Controllers\Admincp\CommunityController@edit'
    ])->where('id', '[0-9]+');


    /***User routes***/
    Route::any('user/list', [
        'as' => 'admincp-user-list',
        'uses' => 'App\Controllers\Admincp\UserController@lists'
    ]);

    Route::any('user/ban', [
        'as' => 'admincp-user-ban',
        'uses' => 'App\Controllers\Admincp\UserController@ban'
    ]);

    Route::any('user/banned', [
        'as' => 'admincp-ban-users',
        'uses' => 'App\Controllers\Admincp\UserController@banUsers'
    ]);

    Route::any('user/unvalidated', [
        'as' => 'admincp-user-unvalidated-list',
        'uses' => 'App\Controllers\Admincp\UserController@unvalidated'
    ]);

    Route::any('user/edit/{id}', [
        'as' => 'admincp-user-edit',
        'uses' => 'App\Controllers\Admincp\UserController@edit'
    ])->where('id', '[0-9]+');

    Route::any('user/custom-field', [
        'as' => 'admincp-user-custom-field',
        'uses' => 'App\Controllers\Admincp\UserController@customFields'
    ]);

    Route::any('user/custom-field/add', [
        'as' => 'admincp-user-custom-field-add',
        'uses' => 'App\Controllers\Admincp\UserController@addCustomFields'
    ]);

    Route::any('user/custom-field/edit/{id}', [
        'as' => 'admincp-user-custom-field-edit',
        'uses' => 'App\Controllers\Admincp\UserController@editCustomFields'
    ])->where('id', '[0-9]+');

    Route::any('user/custom-field/delete/{id}', [
        'as' => 'admincp-user-custom-field-delete',
        'uses' => 'App\Controllers\Admincp\UserController@deleteCustomFields'
    ])->where('id', '[0-9]+');

    /**Reports***/
    Route::any('reports', [
        'as' => 'admincp-reports',
        'uses' => 'App\Controllers\Admincp\ReportController@lists'
    ]);

    Route::any('reports/delete/{id}', [
        'as' => 'delete-report',
        'uses' => 'App\Controllers\Admincp\ReportController@delete'
    ])->where('id', '[0-9]+');

    //additional pages
    Route::any('additional-page', [
        'as' => 'admincp-additional-page',
        'uses' => 'App\Controllers\Admincp\AdditionalPageController@edit'
    ]);

    //Help System
    Route::any('helps/list', [
        'as' => 'admincp-helps',
        'uses' => 'App\Controllers\Admincp\HelpController@index'
    ]);

    Route::any('helps/add', [
        'as' => 'admincp-helps-add',
        'uses' => 'App\Controllers\Admincp\HelpController@add'
    ]);

    Route::any('helps/edit/{id}', [
        'as' => 'admincp-helps-edit',
        'uses' => 'App\Controllers\Admincp\HelpController@edit'
    ])->where('id', '[0-9]+');

    Route::any('helps/delete/{id}', [
        'as' => 'admincp-helps-delete',
        'uses' => 'App\Controllers\Admincp\HelpController@delete'
    ])->where('id', '[0-9]+');

    //page system
    Route::any('pages', [
        'as' => 'admincp-pages',
        'uses' => 'App\\Controllers\\Admincp\\PageController@index'
    ]);

    Route::any('pages/edit/{id}', [
        'as' => 'admincp-pages-edit',
        'uses' => 'App\\Controllers\\Admincp\\PageController@editPage'
    ])->where('id', '[0-9]+');

    Route::any('pages/categories', [
        'as' => 'admincp-pages-categories',
        'uses' => 'App\\Controllers\\Admincp\\PageController@categories'
    ]);

    Route::any('pages/create/category', [
        'as' => 'admincp-pages-create-category',
        'uses' => 'App\\Controllers\\Admincp\\PageController@createCategory'
    ]);

    Route::any('pages/category/edit/{id}', [
        'as' => 'admincp-pages-edit-category',
        'uses' => 'App\\Controllers\\Admincp\\PageController@editCategory'
    ])->where('id', '[0-9]+');

    Route::any('pages/category/delete/{id}', [
        'as' => 'admincp-pages-delete-category',
        'uses' => 'App\\Controllers\\Admincp\\PageController@deleteCategory'
    ])->where('id', '[0-9]+');

    //GAMES ROUTE
    Route::any('games', [
        'as' => 'admincp-games',
        'uses' => 'App\\Controllers\\Admincp\\GameController@index'
    ]);

    Route::any('games/add', [
        'as' => 'admincp-games-add',
        'uses' => 'App\\Controllers\\Admincp\\GameController@add'
    ]);


    Route::any('games/edit/{id}', [
        'as' => 'admincp-games-edit',
        'uses' => 'App\\Controllers\\Admincp\\GameController@editGame'
    ])->where('id', '[0-9]+');

    Route::any('games/approve/{id}', [
        'as' => 'admincp-games-approve',
        'uses' => 'App\\Controllers\\Admincp\\GameController@approveGame'
    ])->where('id', '[0-9]+');

    Route::any('games/categories', [
        'as' => 'admincp-games-categories',
        'uses' => 'App\\Controllers\\Admincp\\GameController@categories'
    ]);

    Route::any('games/create/category', [
        'as' => 'admincp-games-create-category',
        'uses' => 'App\\Controllers\\Admincp\\GameController@createCategory'
    ]);

    Route::any('games/category/edit/{id}', [
        'as' => 'admincp-games-edit-category',
        'uses' => 'App\\Controllers\\Admincp\\GameController@editCategory'
    ])->where('id', '[0-9]+');

    Route::any('games/category/delete/{id}', [
        'as' => 'admincp-games-delete-category',
        'uses' => 'App\\Controllers\\Admincp\\GameController@deleteCategory'
    ])->where('id', '[0-9]+');

    //newsletter
    Route::any('newsletter', [
        'as' => 'admincp-newsletter',
        'uses' => 'App\\Controllers\\Admincp\\NewsletterController@index'
    ]);

    Route::any('newsletter/add', [
        'as' => 'admincp-newsletter-add',
        'uses' => 'App\\Controllers\\Admincp\\NewsletterController@add'
    ]);

    Route::any('newsletter/resent/{id}', [
        'as' => 'admincp-newsletter-resend',
        'uses' => 'App\\Controllers\\Admincp\\NewsletterController@resend'
    ])->where('id', '[0-9]+');

    Route::any('newsletter/delete/{id}', [
        'as' => 'admincp-newsletter-delete',
        'uses' => 'App\\Controllers\\Admincp\\NewsletterController@delete'
    ])->where('id', '[0-9]+');


    //ads
    Route::any('ads', [
        'as' => 'admincp-ads',
        'uses' => 'App\\Controllers\\Admincp\\AdsController@index'
    ]);

});

/**
 * Admincp Login
 */
Route::any('admincp/login', [
    'as' => 'admincp-login',
    'uses' => 'App\Controllers\Admincp\LoginController@index'
]);

/**
 * Change of language
 */
Route::any('change/language/{var}', [
    'as' => 'change-language',
    'uses' => 'App\Controllers\Admincp\LanguageController@change'
])->where('var', '[a-zA-Z\-\_]+');