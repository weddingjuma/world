<?php

return [
    'enable-cdn' => [
        'type' => 'boolean',
        'title' => 'Enable CDN (Content Delivery Network)',
        'description' => 'Enable content delivery network for your members media files e.g photos,videos,files e.t.c',
        'value' => '0',
    ],

    'cdn-driver' => [
        'type' => 'dropdown',
        'title' => 'CDN Preferred Driver',
        'description' => 'Select your preferred CDN Driver, please make sure you set it up below',
        'value' => 'amazon',
        'data' => [
            'amazon' => 'Amazon S3',
            'self' => 'Self Hosted CDN'
        ]
    ],

    'keep-local-files' => [
        'type' => 'boolean',
        'title' => 'Do You Want To Keep Files On Both CDN And This Server',
        'description' => 'Set if you want to keep files on both CDN and this server',
        'value' => '0',
    ],


    'amazon-bucket' => [
        'type' => 'text',
        'title' => 'Your Amazon S3 Bucket',
        'description' => 'Set your amazon S3 Bucket name, please read the documentation for more details',
        'value' => '',
    ],

    'amazon-id' => [
        'type' => 'text',
        'title' => 'Your Amazon S3 Access Key Id',
        'description' => 'Set your amazon S3 Access Key Id, please read the documentation for more details',
        'value' => '',
    ],

    'amazon-access-key' => [
        'type' => 'text',
        'title' => 'Your Amazon S3 Secret Access Key',
        'description' => 'Set your amazon S3 Secret Access Key, please read the documentation for more details',
        'value' => '',
    ],

    'amazon-endpoint-url' => [
        'type' => 'text',
        'title' => 'Set Your Amazon S3 Endpoint URL',
        'description' => 'Set your amazon S3 endpoint url without http or https e.g <b>s3.amazonaws.com</b>',
        'value' => 's3.amazonaws.com',
    ],


    'cdn-self-base-url' => [
        'type' => 'text',
        'title' => 'CDN Self Hosted Base URL',
        'description' => 'Set your CDN self hosted base url Please read the documentation for more details',
        'value' => '',
    ],

    'cdn-self-key' => [
        'type' => 'text',
        'title' => 'CDN Self Hosted Secret Key',
        'description' => 'Set your CDN self hosted secret key Please read the documentation for more details',
        'value' => 'fdhskjfhsfkjhkjHKSJDHFKJSHFKSJDFHKSJHKJ21U1Y8973213987sagdasjgadshgah',
    ],

    'cdn-self-processor' => [
        'type' => 'text',
        'title' => 'CDN Self Hosted Processor File Name',
        'description' => 'Set your CDN self hosted processor file name Please read the documentation for more details',
        'value' => 'processor',
    ],
];