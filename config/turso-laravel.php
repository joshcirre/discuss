<?php

// config for RichanFongdasen/TursoLaravel
return [
    'client' => [
        'connect_timeout' => env('TURSO_CONNECT_TIMEOUT', 2),
        'timeout' => env('TURSO_REQUEST_TIMEOUT', 5),
    ],

    'sync_command' => [
        'node_path' => env('NODE_PATH'), // Escape the node path to handle spaces
        'script_filename' => 'turso-sync.mjs',
        'script_path' => realpath(__DIR__.'/..'),
        'timeout' => 60,
    ],
];
