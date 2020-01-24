<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Server Requirements
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel server requirements, you can add as many
    | as your application require, we check if the extension is enabled
    | by looping through the array and run "extension_loaded" on it.
    |
    */
    'requirements' => [
        'openssl',
        'pdo',
        'mbstring',
        'tokenizer',
        'ionCube Loader',
        'ldap',
        'imap',
        'gd',
        'fileinfo',
        'curlr'
    ],

    /*
    |--------------------------------------------------------------------------
    | Folders Permissions
    |--------------------------------------------------------------------------
    |
    | This is the default Laravel folders permissions, if your application
    | requires more permissions just add them to the array list bellow.
    |
    */
    'permissions' => [
        'storage/app/'           => '775',
        'storage/framework/'     => '775',
        'storage/logs/'          => '775',
        'storage/tmp/'           => '775',
        'storage/users/'         => '775',
        'bootstrap/cache/'       => '775', 
        'nodejs/'                => '775'
    ]
];
