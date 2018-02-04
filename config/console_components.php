<?php
return [
    'log' => [
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
//                'levels' => ['trace','info','profile','warning','error'],
            ],
        ],
    ],
    'db_cqr' => [
        'class' => 'yii\db\Connection',
        'charset' => 'utf8',
        'attributes' => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_pro' => [
        'class' => 'yii\db\Connection',
        'charset' => 'utf8',
        'attributes' => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_xqb' => [
        'class' => 'yii\db\Connection',
        'charset' => 'utf8',
        'attributes' => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_passport_0' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=passport_0',
        'username'      => 'passport_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_passport_1' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=passport_1',
        'username'      => 'passport_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_passport_2' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=passport_2',
        'username'      => 'passport_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_passport_3' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=passport_3',
        'username'      => 'passport_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_passport_4' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=passport_4',
        'username'      => 'passport_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_passport_5' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=passport_5',
        'username'      => 'passport_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_passport_6' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=passport_6',
        'username'      => 'passport_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_passport_7' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=passport_7',
        'username'      => 'passport_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_passport_8' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=passport_8',
        'username'      => 'passport_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_passport_9' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=passport_9',
        'username'      => 'passport_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_quest_const' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=question_const',
        'username'      => 'quest_const_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_question_chinese' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=question_chinese',
        'username'      => 'question_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_question_math' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=question_math',
        'username'      => 'question_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_question_english' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=question_english',
        'username'      => 'question_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_question_physical' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=question_physical',
        'username'      => 'question_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_question_chemistry' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=question_chemistry',
        'username'      => 'question_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_question_biological' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=question_biological',
        'username'      => 'question_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_question_political' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=question_political',
        'username'      => 'question_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_question_history' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=question_history',
        'username'      => 'question_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_question_geography' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=question_geography',
        'username'      => 'question_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_question_common_technology' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=question_common_technology',
        'username'      => 'question_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
    'db_question_internet_technology' => [
        'dsn'           => 'mysql:host=123.56.156.172; dbname=question_internet_technology',
        'username'      => 'question_rd',
        'password'      => 'Wzj769397',
        'class'         => 'yii\db\Connection',
        'charset'       => 'utf8',
        'attributes'    => [
            PDO::ATTR_TIMEOUT => 1,
        ],
    ],
];