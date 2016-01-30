<?php

return [
    'auto-follow-friends' => [
        'type' => 'boolean',
        'title' => 'Automatic Follow Friends',
        'description' => 'Do you want your members who are friends follow each other, you can enable/disable it with this configuration',
        'value' => '1',
    ],

    'maximum-friends' => [
        'type' => 'text',
        'title' => 'Member Friends Limit',
        'description' => 'Set the maximum number of friends your member can have, to disable set it to 0 ',
        'value' => '0',
    ],
];