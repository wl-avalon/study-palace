<?php
/**
 * author : wangzhengjun
 * QQ     : 694487069
 * phone  : 15801450732
 * Email  : wangzjc@jiedaibao.com
 * Date   : 17/6/7
 */
namespace ploanframework\components;
use ploanframework\constants\JdbErrors;
use rrxframework\base\JdbException;
use rrxframework\base\JdbLog;

class Assert
{
    public static function isTrue($bool, $msg, $logMsg = "", $errCode = JdbErrors::ERR_NO_SERVER_BUSY)
    {
        if ( $bool === false ){
            if ( "" != $logMsg ){
                JdbLog::warning("当前断言判断为假, userMsg: $msg , log:".$logMsg."errorCode:$errCode", 0, 0, 1);
            }
            throw new JdbException($errCode, null, $msg, $logMsg);
        }
    }
}