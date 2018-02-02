<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/2
 * Time: 下午3:35
 */

namespace app\components;


class SPLog
{
    private static $logFile = null;

    public static function log($test){
        if(is_null(self::$logFile)){
            $logFilePath = dirname(__DIR__) . "/logs/sp.log";
            self::$logFile = fopen($logFilePath, 'a');
        }
        fwrite(self::$logFile, $test);
    }
}