<?php

$config = [
    'id'         => 'ploanframework',
    'timeZone'   => 'Asia/Shanghai',
    'basePath'   => dirname(dirname(__DIR__)),
    'bootstrap'  => ['log'],
    'components' => include(__DIR__ . '/components.php'),
    'params'     => include(__DIR__ . '/params.php'),
    'modules'    => [
        'ploanframework' => [
            'class' => 'ploanframework\Module',
        ],
    ],
    'aliases'    => [
        '@rrxframework'   => '@app/rrxframework',
        '@ploanframework' => '@app/ploanframework',
    ],
];

return $config;
