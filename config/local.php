<?php

$databaseConfig = [
    'host' => getenv('MYSQL_HOST'),
    'port' => getenv('MYSQL_PORT'),
    'username' => getenv('MYSQL_USER'),
    'password' => getenv('MYSQL_PASSWORD'),
    'dbname' => getenv('MYSQL_DB'),
];

$config = [
    'config' => [
        'database' => [
            'params' => $databaseConfig,
            'dsn' => 'mysql:host=' . $databaseConfig['host'] . ';port='
                . $databaseConfig['port'] . ';dbname=' . $databaseConfig['dbname'],
        ],
        'secret' => getenv('PWD_SECRET')
    ],
];

$appConfig = (new \Gouh\BlogApi\App\ConfigProvider())();
$appConfig['dependencies'] = array_merge($appConfig['dependencies'], $config);
return $appConfig;
