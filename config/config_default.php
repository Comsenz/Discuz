<?php

return [
    'debug' => true,
    'locale' => 'zh_CN',
    'timezone' => 'Asia/Shanghai',
    'database' =>
        [
            'driver' => 'mysql',
            'host' => 'localhost',
            'port' => '3306',
            'database' => 'discuss',
            'username' => 'root',
            'password' => '123',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => '',
            ]) : [],
        ],
    'cache' => [
        'default' => 'file',

        'stores' => [
            'file' => [
                'driver' => 'file',
                'path' => storage_path('cache/data'),
            ],
        ],

        'prefix' => 'discuz_cache',

    ],
    'filesystems' => [
        'default' => 'local',
        'cloud' => '',
        'disks' => [
            'local' => [
                'driver' => 'local',
                'root' => storage_path('app'),
            ],
            'attachment' => [
                'driver' => 'local',
                'root'   => storage_path('public/attachment'),
                'url'    => 'attachment'
            ],

            'public' => [
                'driver' => 'local',
                'root' => storage_path('app/public'),
                'url' => 'storage',
                'visibility' => 'public',
            ],
            'cos' => [
                'driver' => 'cos',
                'region' => 'ap-beijing', //设置一个默认的存储桶地域
                'schema' => 'https', //协议头部，默认为http
                'bucket' => 'bucket',
                'credentials'=> [
                    'secretId'  => 'COS_SECRETID',  //"云 API 密钥 SecretId";
                    'secretKey' => 'COS_SECRETKEY', //"云 API 密钥 SecretKey";
                    'token' => 'token' //"临时密钥 token";
                ]
            ]
        ]
    ],
];
