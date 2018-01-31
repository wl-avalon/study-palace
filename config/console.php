<?php
$config = [
    'id' => 'study_palace_console',
    'timeZone'=>'Asia/Shanghai',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'components' => include(__DIR__ . '/console_components.php'),
    'params' => include (__DIR__ . '/params.php'),
    'aliases' => [
        '@rrxframework' => '@app/rrxframework',
        '@ploanframework' => '@app/ploanframework',
    ],
];
return $config;
