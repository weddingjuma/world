<?php

return [
    'site-mail-driver' => [
        'type' => 'dropdown',
        'title' => 'Mail Driver',
        'description' => 'Select the mail driver you will like to use',
        'value' => 'mail',
        'data' => [
            'mail' => 'Mail',
            'smtp' => 'SMTP'
        ]
    ],

    'mail-driver-host' => [
        'type' => 'text',
        'title' => 'SMTP Mail Driver Host',
        'description' => 'Set the mail driver host if you are using "mail" leave this empty but for smtp e.g smtp.gmail.com',
        'value' => ''
    ],

    'mail-driver-port' => [
        'type' => 'text',
        'title' => 'SMTP Mail Driver Port',
        'description' => 'Set the mail driver port usually 587 you don\'t need to change it',
        'value' => '587'
    ],

    'site-mail-encryption' => [
        'type' => 'dropdown',
        'title' => 'Mail Driver Encryption',
        'description' => 'Select the mail driver Encryption',
        'value' => 'mail',
        'data' => [
            '' => 'None',
            'ssl' => 'SSL',
            'tls' => 'TLS',
        ]
    ],

    'mail-from-address' => [
        'type' => 'text',
        'title' => 'Mail From Email Address',
        'description' => 'Set the from email address for the mails that will be send',
        'value' => 'no-reply@site.com'
    ],

    'mail-from-name' => [
        'type' => 'text',
        'title' => 'Mail From Name',
        'description' => 'Set the mail from name',
        'value' => ''
    ],

    'mail-driver-username' => [
        'type' => 'text',
        'title' => 'SMTP Username',
        'description' => 'Set the smtp username',
        'value' => ''
    ],

    'mail-driver-password' => [
        'type' => 'text',
        'title' => 'SMTP Mail Password',
        'description' => 'Set the smtp mail password',
        'value' => ''
    ],

];