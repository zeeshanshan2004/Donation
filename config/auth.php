<?php

return [

    'defaults' => [
        // 👇 Default guard ko 'api' kar rahe hain
        'guard' => 'api',
        'passwords' => 'users',
    ],

      'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'admin' => [
        'driver' => 'session',
        'provider' => 'admins',
    ],





        // 👇 JWT ke liye api guard
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users',
        ],
    ],

    'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],

    'admins' => [         // ✅ ye bhi add karo
        'driver' => 'eloquent',
        'model' => App\Models\Admin::class,
    ],
],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
