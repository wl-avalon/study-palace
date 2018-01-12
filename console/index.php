<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/12
 * Time: 下午7:28
 */
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/console.php';

(new yii\console\Application($config))->run();