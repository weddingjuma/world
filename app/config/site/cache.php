<?php
return [
    'enable-query-cache' => [
        'type' => 'boolean',
        'title' => 'Enable Cache Of Queries',
        'description' => 'Caching keeps the site from display purely live content',
        'value' => '1',
    ],

    'query-cache-time-out' => [
        'type' => 'int',
        'title' => 'Cache Of Queries Time Out',
        'description' => 'Set time out for caching of queries in minutes',
        'value' => '5',
    ],

    'post-cache-time-out' => [
        'type' => 'int',
        'title' => 'Cache Of Queries Time Out For Post',
        'description' => 'Set time out for caching of queries in minutes for each posts',
        'value' => '3',
    ],
];