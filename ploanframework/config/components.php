<?php
return [
    'request'      => [// !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
        'cookieValidationKey'  => 'fa3e794d9e06350b76ad6f0943052a28',
        'enableCsrfValidation' => false,
    ],
    'errorHandler' => [
        'errorAction' => 'error/catchall',
    ],
    'log'          => [
        'class'      => 'rrxframework\ext\log\JdbYiiLogDispatcher',
        'traceLevel' => 16,
        'logger'     => 'rrxframework\ext\log\JdbYiiLogger',
        'targets'    => [
            [
                'class'     => 'rrxframework\ext\log\JdbYiiLogFileTarget',
                'log_level' => 16,
                'log_path'  => dirname(dirname(dirname(__DIR__))) . '/logs',
            ],
        ],
    ],
    'urlManager'   => [
        'class'           => 'yii\web\UrlManager',
        'enablePrettyUrl' => true,
        'showScriptName'  => false,
    ],
];