<?php

return [
    'debug' => false,
    'database' =>
        [
            'driver' => 'mysql',
            'host' => 'localhost',
            'port' => '3306',
            'database' => 'discuz',
            'username' => 'root',
            'password' => '123',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => 'fl_',
            'prefix_indexes' => true,
            'strict' => true,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => '',
            ]) : [],
        ],
];
