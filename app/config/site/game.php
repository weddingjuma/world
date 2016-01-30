<?php

return [

    'disable-game' => [
        'type' => 'boolean',
        'title' => 'Disable Game System',
        'description' => 'Set if you want disable game system',
        'value' => '0',
    ],

    'disable-game-embed-code' => [
        'type' => 'boolean',
        'title' => 'Disable Embedding Of Games',
        'description' => 'Set if you want disable game embed code',
        'value' => '1',
    ],

    'allow-admin-embed-game-code' => [
        'type' => 'boolean',
        'title' => 'Allow Admin Embedding Of Games',
        'description' => 'Set if you want to allow only admins to embed games',
        'value' => '0',
    ],

    'game-max-upload' => [
        'type' => 'integer',
        'title' => 'Game Maximum Upload Size',
        'description' => 'Set maximum upload size for games ',
        'value' => '10000000',
    ],

    'game-create-allowed' => [
        'type' => 'boolean',
        'title' => 'Can Your Member Create Games',
        'description' => 'Set if you want your member to create game else on admin can add games',
        'value' => '1',
    ],

    'game-need-confirm' => [
        'type' => 'boolean',
        'title' => 'Game Confirmation',
        'description' => 'Set if your members created games need confirmation',
        'value' => '0',
    ],

];