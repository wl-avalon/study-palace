<?php

$params = require(__DIR__ . '/params.php');

return [
    'id'                  => 'application-console',
    'basePath'            => dirname(dirname(__DIR__)),
    'bootstrap'           => ['log', 'gii'],
    'controllerNamespace' => 'app\modules\ploanframework\commands',
    'components'          => [
        'errorHandler' => [
            'errorAction' => 'error/catchall',
        ],
        'log'          => [
            'class'      => 'app\rrx\ext\log\JdbYiiLogDispatcher',
            'traceLevel' => 4,
            'logger'     => 'app\rrx\ext\log\JdbYiiLogger',
            'targets'    => [
                [
                    'class'     => 'app\rrx\ext\log\JdbYiiLogFileTarget',
                    'log_level' => 4,
                    'log_path'  => dirname(dirname(dirname(__DIR__))) . '/logs',
                ],
            ],
        ],
    ],
    'modules'             => [
        'ploanframework' => [
            'class' => 'ploanframework\Module',
        ],
        'gii'            => [
            'class' => 'yii\gii\Module',
        ],
    ],
    'aliases'             => [
        '@rrxframework'   => '@app/rrxframework',
        '@ploanframework' => '@app/ploanframework',
    ],
    'params'              => $params,
];
