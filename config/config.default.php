<?php

return [
    'settings' => [
        'displayErrorDetails' => false,
        'cache' => [
            'engine' => \WebSK\Cache\Engines\Memcache::class,
            'cache_key_prefix' => 'skif',
            'servers' => [
                [
                    'host' => 'localhost',
                    'port' => 11211
                ]
            ]
        ],
        'db' => [
            'db_keyvalue' => [
                'host' => 'localhost',
                'db_name' => 'keyvalue',
                'user' => 'root',
                'password' => 'root',
            ],
            'db_auth' => [
                'host' => 'localhost',
                'db_name' => 'auth',
                'user' => 'root',
                'password' => 'root',
            ],
            'db_logger' => [
                'host' => 'localhost',
                'db_name' => 'logger',
                'user' => 'root',
                'password' => 'root',
            ],
        ],
        'log_path' => '/var/www/log',
        'tmp_path' => '/var/www/tmp',
        'site_domain' => 'http://localhost',
        'site_full_path' => '/var/www/php-keyvalue',
        'site_name' => 'PHP KeyValue Demo',
        'site_title' => 'WebSK. PHP KeyValue Demo',
        'site_email' => 'support@websk.ru',
        'keyvalue' => [
            'layout_main' => '/var/www/php-keyvalue/views/layouts/layout.main.tpl.php',
            'layout_skif' => '/var/www/php-keyvalue/views/layouts/layout.main.tpl.php',
            'main_page_url' => '/',
            'skif_main_page_url' => '/admin'
        ]
    ],
];
