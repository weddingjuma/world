<?php

return [
    'site_title' => [
        'type' => 'text',
        'title' => 'Site title',
        'description' => 'This is your site title',
        'value' => 'creA8Social',
    ],
    'site_description' => [
        'type' => 'textarea',
        'description' => 'This is a short description of your site which is readable by search engines for listing',
        'title' => 'Your site description',
        'value' => 'creA8Social is a social networking platform that provide you with great unique features that you can\'t find anywhere',
    ],
    'site_keywords' => [
        'type' => 'textarea',
        'description' => 'This is your site keywords needed by search engines for listing, seperate them by comma (,)',
        'title' => 'Your site keywords',
        'value' => 'social networking platform, php script, php software, php social networking script'
    ],

    'site_email' => [
        'type' => 'text',
        'description' => 'Set your site email use to send mail to your members',
        'title' => 'Site Email Address',
        'value' => ''
    ],

    'realtime-check-interval' => [
        'type' => 'dropdown',
        'title' => 'Set RealTime Check Interval ',
        'description' => 'Set realtime check interval',
        'value' => '30',
        'data' => [
            '10000' => '10 Seconds',
            '20000' => '20 Seconds',
            '30000' => '30 Seconds',
            '40000' => '40 Seconds',
            '50000' => '50 Seconds',
            '60000' => '1 Minute',
            '120000' => '2 Minutes',
            '180000' => '3 Minutes',
            '240000' => '4 Minutes',
            '300000' => '5 Minutes'
        ]
    ],

    'enable-https' => [
        'type' => 'boolean',
        'description' => 'Option to enable secure url for your website through HTTPS',
        'title' => 'Enable HTTPS',
        'value' => '0'
    ],

    'timezone' => [
        'type' => 'dropdown',
        'title' => 'TimeZone',
        'description' => 'Set your prefer timezone by checking',
        'value' => 'UTC',
        'data' => timezoneList()
    ],


    'predefined-words' => [
        'type' => 'textarea',
        'title' => 'Predefined Words',
        'description' => 'Prevent your members from using certain words, list them by separating them with comma (,), <strong>Note: all in small letter</strong>',
        'value' => 'themes,app,vendor,fuck,games,admin,administrator,config,workbench,uploads,upgrade-info,bootstrap,guest'
    ],

    'google-analytics-code' => [
        'type' => 'textarea',
        'description' => 'Set your google analytics code here',
        'title' => 'Add Google Analytics Code',
        'value' => ''
    ],

    'enable-debug' => [
        'type' => 'boolean',
        'title' => 'Enable Debug Mode',
        'description' => 'Enable debug to detect the cause of errors ',
        'value' => '0',
    ],

    'maintenance-mode' => [
        'type' => 'boolean',
        'title' => 'Enable Maintenance Mode',
        'description' => 'Enable maintenance mode for your frontend visitors, only admin pages will be accessible',
        'value' => '0',
    ],

    'maintenance-mode-text' => [
        'type' => 'textarea',
        'title' => 'Maintenance Mode Status Text',
        'description' => 'Additional maintenance mode status text for your frontend visitors, only admin pages will be accessible',
        'value' => 'We should be back shortly, Thanks for your patience',
    ],

    'ajaxify_frontend' => [
        'type' => 'boolean',
        'title' => 'Ajaxify Frontend',
        'description' => 'Ajaxify frontend, user frontend pages will be loaded using ajax technology',
        'value' => 1
    ],
    'minify-assets' => [
        'type' => 'boolean',
        'title' => 'Use Compressed Assets',
        'description' => 'Compressing assets increases page load speed',
        'value' => '1'
    ],
    'remove-search-bar-from-home' => [
        'type' => 'boolean',
        'title' => 'Remove search box from Homepage',
        'description' => 'With this setting you can easily remove the header search box from homepage',
        'value' => 0
    ]
];