<?php
$config = [
    'id' => 'study_palace',
    'timeZone'=>'Asia/Shanghai',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@rrxframework' => '@app/../rrxframework',
        '@ploanframework' => '@app/../ploanframework',
    ],
    'components' => include(__DIR__ . '/components.php'),
    'params' => include (__DIR__ . '/params.php'),
];
return $config;
