<?php

use Cycle\Database\Config\SQLite\FileConnectionConfig;
use Cycle\Database\Config\SQLiteDriverConfig;

return [
    // データベース
    'database' => [
        'default' => 'default',
        'databases' => [
            'default' => ['connection' => 'sqlite'],
        ],
        'connections' => [
            'sqlite' => new SQLiteDriverConfig(
                connection: new FileConnectionConfig(
                    database: __DIR__ . '/../' . env('DB_DATABASE'),
                ),
                queryCache: true,
            ),
        ],
    ],

    // ORM
    'orm' => [
        'tokenizer' => [
            'directories' => [
                __DIR__ . '/../app',
            ],

            'exclude' => [],
        ],
    ],

    // マイグレーション
    'migrations' => [
        'directory' => __DIR__ . '/../migration',
        'table' => 'migrations',
        'safe' => env('APP_ENV') !== 'production',
    ],
];
