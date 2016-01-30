<?php

return [
    'user-listing' => [
        'type' => 'int',
        'title' => 'Users listing limit',
        'description' => 'Users listing limit',
        'value' => '10',
    ],
    'user-signup' => [
        'type'  => 'boolean',
        'title' => 'Enable/Disable user registration',
        'description' => 'You can disable or enable registration to your network',
        'value' => 0
    ],

    'user-minimum-age' => [
        'type' => 'text',
        'title' => 'User Minimum Age To Register',
        'description' => 'Set user minimum age they must have to register, to disable set it to Zero (0)',
        'value' => '18'
    ],

    'birth-year-max' => [
        'type' => 'int',
        'title' => 'Enter the Maximum Year For User Birth Date',
        'description' => 'Set the maximum year users can select when setting birthdate (Leave empty for current year)',
        'value' => '1999'
    ],

    'birth-year-min' => [
        'type' => 'int',
        'title' => 'Enter the Minimum Year For User Birth Date',
        'description' => 'Set the minimum year users can select when setting birthdate (Default is 1940)',
        'value' => '1940'
    ],


    'enable-captcha' => [
        'type'  => 'boolean',
        'title' => 'Enable Captcha',
        'description' => 'You can disable or enable captcha in forms',
        'value' => 1
    ],


    'user-activation' => [
        'type' => 'boolean',
        'title' => 'User account activation',
        'description' => 'Enable/Disable is user must activate there account through email',
        'value' => 0
    ],

    'user-getstarted' => [
        'type' => 'boolean',
        'title' => 'Enable/Disable User Getstarted',
        'description' => 'Enable/Disable user getstarted feature',
        'value' => 1
    ],

    'remove-verify-badge-username' => [
        'type' => 'boolean',
        'title' => 'Enable/Disable Removal of Verified Badge when user change username',
        'description' => 'Enable/Disable removal of verified badge when user change username',
        'value' => 1
    ],

    'can-change-username' => [
        'type' => 'boolean',
        'title' => 'Enable/Disable Changing Of Username',
        'description' => 'Enable/Disable Changing of username',
        'value' => 1
    ],

    'page-design' => [
        'type' => 'boolean',
        'title' => 'Enable/Disable Page Design',
        'description' => 'Enable/Disable if you want your member to be able design there pages',
        'value' => 1
    ],

    'users-autofollow' => [
        'type' => 'textarea',
        'title' => 'Auto Follow Users For New Members',
        'description' => 'Set the username seperated by (,) of users you want your new members to auto follow',
        'value' => ''
    ],

    'community-autojoin' => [
        'type' => 'textarea',
        'title' => 'Auto Join Communities For New Members',
        'description' => 'Set the community slug seperated by (,) of communities you want your new members to auto join',
        'value' => ''
    ],

    'pages-autolike' => [
        'type' => 'textarea',
        'title' => 'Auto Like Pages For New Members',
        'description' => 'Set the pages slug seperated by (,) of pages you want your new members to auto like',
        'value' => ''
    ],

    'user-badwords' => [
        'type' => 'textarea',
        'title' => 'User Disallowed words [BAD WORDS]',
        'description' => 'Set user badwords, seperate them by comma',
        'value' => 'fuck,sex,xxx,fucku,motherfucker,bitch,fuckme,ass,fuckass'
    ],

    'user-badwords-replace' => [
        'type' => 'text',
        'title' => 'User Disallowed words [BAD WORDS] Replace text',
        'description' => 'Set user badwords replace text',
        'value' => '***'
    ],

    ///version 4.0
    'user-enable-birth-date' => [
        'type' => 'boolean',
        'title' => 'Enable Birth Date',
        'description' => 'Option to enable or disable birth date',
        'value' => 1
    ],



];