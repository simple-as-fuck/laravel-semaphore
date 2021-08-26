<?php

declare(strict_types=1);

return [
    'store' => env('LOCK_STORE', 'semaphore'),

    'pgsql_store' => [
        'connection' => env('LOCK_PGSQL_STORE_CONNECTION'),
    ],
];
