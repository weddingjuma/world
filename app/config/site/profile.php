<?php

return [
    'profile-url-format' => [
        'type' => 'dropdown',
        'title' => 'User Profile Url Format',
        'description' => 'Set the type of user profile url format(e.g site.com/2 or site.com/username',
        'value' => '2',
        'data' => [
            '2' => 'site.com/username'
        ]
    ],

    'disable-guest-access-profile' => [
        'type' => 'boolean',
        'title' => 'Disable Guest Access Of Your Members Profile',
        'description' => 'Disable Guest Access Of Your Members Profile',
        'value' => 0
    ],

];