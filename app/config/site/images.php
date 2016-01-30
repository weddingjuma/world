<?php

return [
    'image-max-size' => [
        'type' => 'int',
        'title' => 'Maximum Image Size Allowed',
        'description' => 'Set the maximum image size allowed',
        'value' => '2000000',
    ],
    'image-allow-type' => [
        'type' => 'text',
        'title' => 'Allow Image Types',
        'description' => 'Set the allowed images types seperating with comma (,)',
        'value' => 'jpg,png,gif,jpeg',
    ],

    'allow-animated-gif' => [
        'type' => 'boolean',
        'title' => 'Allow Animated Gif',
        'description' => 'Set if you want your members to upload animated images to your site',
        'value' => '1',
    ],

    'edit-avatar' => [
        'type' => 'boolean',
        'title' => 'Allow Edit Of Entity Avatars/Photos',
        'description' => 'Set if allow editing of entity avatar/photos',
        'value' => '1',
    ],
];