<?php

return [
    'enable-oembed' => [
        'type' => 'boolean',
        'title' => 'Enable OEmbed (URL Expanding System) In Posts',
        'description' => 'Use this option to enable oembed in post for powerful Url Expanding System ',
        'value' => true
    ],

    'max-images-per-post' => [
        'type' => 'int',
        'title' => 'Maximum Number Of Images Per Post',
        'description' => 'Set the maximum image allowed per post',
        'value' => '8',
    ],

    'post-per-page' => [
        'type' => 'int',
        'title' => 'Post Per page',
        'description' => 'Set number of post to show per page',
        'value' => 10
    ],

    'enable-public-post' => [
        'type' => 'boolean',
        'title' => 'Enable/Disable Public Post For New Members',
        'description' => 'With this settings you can enable public posts for new members',
        'value' => 0
    ],

    'enable-hot-posts' => [
        'type' => 'boolean',
        'title' => 'Enable/Disable List Posts Order By Hottest ',
        'description' => 'Hot posts are posts that receive comments, likes and shares will be at the top on the user timeline',
        'value' => 0
    ],

    'post-tags-member-limit' => [
        'type' => 'int',
        'title' => 'Number of tagged members to display',
        'description' => 'Set the number of tagged members to be visible',
        'value' => 3
    ],

    'enable-post-text-limit' => [
        'type' => 'boolean',
        'title' => 'Enable Post text limit',
        'description' => 'Enable/Disable post text limit',
        'value' => 1
    ],

    'post-text-limit' => [
        'type' => 'int',
        'title' => 'Maximum text allowed in a post',
        'description' => 'Set the maximum character your member can post',
        'value' => 150
    ],

    'post-text-max-show' => [
        'type' => 'int',
        'title' => 'Maximum Post Text to show ',
        'description' => 'Set the maximum post text to show before read more button',
        'value' => 500
    ],

];