<?php

declare(strict_types=1);

if (env('APP_ENV') !== 'local') {
    return [];
}

return [
    'user' => [
        'name'     => 'User',
        'email'    => 'user@example.com',
        'password' => 'demo1234',

    ],
    'admin' => [
        'name'     => 'Admin user',
        'email'    => 'admin@example.com',
        'password' => 'demo1234',
    ],
];
