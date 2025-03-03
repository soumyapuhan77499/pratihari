<?php

return [

    'defaults' => [
        'guard' => 'super_admin',    // Set super_admin as default if desired
        'passwords' => 'users',
    ],



    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
        'admins' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],
        'super_admin' => [        // 👈 Add this
            'driver' => 'session',
            'provider' => 'super_admins',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'admins' => [
        'driver' => 'eloquent',
        'model' => App\Models\Admin::class, // Replace with your Admin model's namespace
        ],
        
        'super_admins' => [        // 👈 Add this
            'driver' => 'eloquent',
            'model' => App\Models\SuperAdmin::class,
        ],
    
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
