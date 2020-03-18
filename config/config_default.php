<?php

use Illuminate\Support\Str;

return [
    'debug' => true,
    'locale' => 'zh-CN',
    'fallback_locale' => 'zh-CN',
    'timezone' => 'Asia/Shanghai',
    'key' => 'base64:JtNRiS14Mopb+HNi3ztxi6259im9DTDBJXOzLDbcquw=',
    'cipher' => 'AES-256-CBC',
    'site_url' => 'DummySiteUrl',
    'database' =>
        [
            'driver' => 'mysql',
            'host' => 'DummyDbHost',
            'port' => 'DummyDbPort',
            'database' => 'DummyDbDatabase',
            'username' => 'DummyDbUsername',
            'password' => 'DummyDbPassword',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => 'DummyDbPrefix',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => 'InnoDB',
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
            'password' => '123',
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
        'default' => 'file', //如果配置的 redis 可用， 会自动切换为redis

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
            'public' => [
                'driver' => 'local',
                'root' => storage_path('app/public'),
                'url' => 'public',
                'visibility' => 'public',
            ],
            'avatar' => [
                'driver' => 'local',
                'root' => storage_path('app/public/avatars'),
                'url' => 'avatar',
                'visibility' => 'public',
            ],
            'attachment' => [
                'driver' => 'cos',
                'root'   => storage_path('app/public/attachment'),
                'url'    => 'attachment'
            ],
            'cos' => [
                'driver' => 'cos',
                'region' => '', //设置一个默认的存储桶地域
                'schema' => 'https', //协议头部，默认为http
                'bucket' => '',
                'read_from_cdn' => false, //是否从cdn读取，如果为true ， 设置cdn地址
                'credentials'=> [
                    'secretId'  => '',  //"云 API 密钥 SecretId";
                    'secretKey' => '', //"云 API 密钥 SecretKey";
                    'token' => '' //"临时密钥 token";
                ]
            ]
        ]
    ],
    'queue' => [
        'default' => 'redis',
        'connections' => [
            'redis' => [
                'driver' => 'redis',
                'connection' => 'default',
                'queue' => 'REDIS_QUEUE',
                'retry_after' => 90,
                'block_for' => null,
            ]
        ]
    ],
    'excel' => [
        'root' => storage_path('public/exports')
    ],
    //加载ServiceProvider
    'providers' => [
        App\Formatter\FormatterServiceProvider::class,
        App\Passport\Oauth2ServiceProvider::class,
        App\Providers\AppServiceProvider::class,
        App\Providers\CategoryServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\OrderServiceProvider::class,
        App\Providers\PostServiceProvider::class,
        App\Providers\SettingsServiceProvider::class,
        App\Providers\ThreadServiceProvider::class,
        App\Providers\UserServiceProvider::class,
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
                'sdk_app_id' => '', // SDK APP ID
                'app_key' => '', // APP KEY
                'sign_name' => '', // 短信签名，如果使用默认签名，该字段可缺省（对应官方文档中的sign）
            ],
        ],
    ]
];
