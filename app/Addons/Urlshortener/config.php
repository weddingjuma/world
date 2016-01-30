 <?php

return [
    'shortener-domain' => [
        'type' => 'text',
        'title' => 'Shortened URL Domain',
        'description' => 'This option to set the domain for the shortened url, the default is your site root url ',
        'value' => '',
    ],

    'shortener-prefix' => [
        'type' => 'text',
        'title' => 'Set The Prefix For Each Shortened Urls',
        'description' => 'This option to set for each urls <strong>Supported ones are _ + - @ to add more please contact me</strong>, and also it can\'t be empty ',
        'value' => '+',
    ],

];