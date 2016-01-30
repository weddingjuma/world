<?php
return [
    'max-upload-files' => [
        'type' => 'int',
        'title' => 'Set The Maximum File Upload Size',
        'description' => 'Option to set maximum file upload size',
        'value' => 250000000
    ],

    'allow-files-types' => [
        'type' => 'text',
        'title' => 'Set The Allow File Types',
        'description' => 'Option to set allow file types separate the with comma (,)',
        'value' => 'exe,txt,zip,rar,doc,mp3,jpg,png,css,psd,pdf,ppt,pptx,xls,xlsx,html,docx,fla,avi,mp4'
    ],

];