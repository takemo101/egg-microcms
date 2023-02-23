<?php

/**
 * その他設定
 */
return [
    // リソースデータを格納するディレクトリパス
    'resource-path' => 'resource',

    // latteのテンプレートを格納するディレクトリパス
    'latte-path' => 'resource/latte',

    // latteのキャッシュを格納するディレクトリパス
    // ストレージディレクトリからの相対パス
    'latte-cache-path' => 'cache/latte',

    // 強制的にhttpsを使用するか
    'force_https' => env('FORCE_HTTPS', false),

    // MicroCMSの設定
    'microcms' => [
        'domain' => env('MICROCMS_DOMAIN', 'xxx'),
        'api-key' => env('MICROCMS_API_KEY', 'xxx'),
    ]
];
