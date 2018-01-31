<?php

$params = require __DIR__ . '/params.php';
$config = [
    'id' => 'study_palace',
    'timeZone'=>'Asia/Shanghai',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => include(__DIR__ . '/components.php'),
    'params' => $params,
];
return $config;
