<?php

return [
    'settings' => [
        'displayErrorDetails' => false,
        'cache' => [
            'engine' => \WebSK\Cache\Engines\Memcached::class,
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
                'dump_file_path' => \WebSK\KeyValue\KeyValueServiceProvider::DUMP_FILE_PATH
            ],
            'db_auth' => [
                'host' => 'localhost',
                'db_name' => 'auth',
                'user' => 'root',
                'password' => 'root',
                'dump_file_path' => \WebSK\Auth\AuthServiceProvider::DUMP_FILE_PATH
            ],
            'db_logger' => [
                'host' => 'localhost',
                'db_name' => 'logger',
                'user' => 'root',
                'password' => 'root',
                'dump_file_path' => \WebSK\Logger\LoggerServiceProvider::DUMP_FILE_PATH
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
            'layout_admin' => '/var/www/php-keyvalue/views/layouts/layout.main.tpl.php',
            'admin_main_page_url' => '/admin',
        ],
        'logger' => [
            'layout_main' => '/var/www/php-logger/views/layouts/layout.main.tpl.php',
            'layout_admin' => '/var/www/php-logger/views/layouts/layout.main.tpl.php',
            'main_page_url' => '/',
            'admin_main_page_url' => '/admin'
        ]
    ],
];
