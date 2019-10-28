<?php

use Illuminate\Support\Str;

return [
    'debug' => true,
    'locale' => 'zh-CN',
    'fallback_locale' => 'zh-CN',
    'timezone' => 'Asia/Shanghai',
    'key' => 'base64:JtNRiS14Mopb+HNi3ztxi6259im9DTDBJXOzLDbcquw=',
    'cipher' => 'AES-256-CBC',
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
    'redis' => [
        'client' => 'phpredis',

        'options' => [
            'cluster' => 'redis',
            'prefix' => Str::slug('discuz', '_').'_database_'
        ],

        'default' => [
            'url' => '',
            'host' => '127.0.0.1',
            'password' => null,
            'port' => 6379,
            'database' => 0
        ],

        'cache' => [
            'url' => '',
            'host' => '127.0.0.1',
            'password' => '123',
            'port' => 6379,
            'database' => 1
        ],
    ],
    //缓存系统配置
    'cache' => [
        'default' => 'file',

        'stores' => [
            'file' => [
                'driver' => 'file',
                'path' => storage_path('cache/data'),
            ],
            'redis' => [
                'driver' => 'redis',
                'connection' => 'cache',
            ],
        ],

        'prefix' => 'discuz_cache',

    ],
    //文件系统配置
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
    //加载ServiceProvider
    'providers' => [
//        App\Providers\EventServiceProvider::class
        App\Providers\EventServiceProvider::class,
        App\Settings\SettingsServiceProvider::class

    ],
    'sms' => [
        // HTTP 请求的超时时间（秒）
        'timeout' => 5.0,

        // 默认发送配置
        'default' => [
            // 网关调用策略，默认：顺序调用
            'strategy' => Overtrue\EasySms\Strategies\OrderStrategy::class,

            // 默认可用的发送网关
            'gateways' => [
                'qcloud'
            ],
        ],
        // 可用的网关配置
        'gateways' => [
            'errorlog' => [
                'file' => storage_path('log/easy-sms.log')
            ],
            'qcloud' => [
                'sdk_app_id' => '1251099537', // SDK APP ID
                'app_key' => 'g7KJ3atwlMlcKn0zpRnYaNvEI7lBQYS4', // APP KEY
                'sign_name' => '', // 短信签名，如果使用默认签名，该字段可缺省（对应官方文档中的sign）
            ],
        ],
    ]
];
