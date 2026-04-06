<?php

return [

    'credentials' => [
        'file' => storage_path(env('FIREBASE_CREDENTIALS')),
    ],

    'database' => [
        'url' => env('FIREBASE_DATABASE_URL', null),
    ],

    'default_storage_bucket' => env('FIREBASE_STORAGE_BUCKET', null),

];
