<?php 
namespace ploanframework\services\risk;

use rrxframework\util\IpUtil;
use ploanframework\apis\ApiContext;
use rrxframework\base\JdbLog;

class RiskService{
    /**
     *http://apidoc.jdb-dev.com/#/details/100/5983/jdb.internet.aeolus.php/100/5983;appName=jdb.internet.aeolus.php
     */
    public static function getPop($memberID, $type ,$raw)
    {
        $apiConfig = \Yii::$app->params;
        if (!isset($apiConfig['risk']) || !isset($apiConfig['risk']['apis']['windTips'])) {
            JdbLog::warning('no config -risk windTips');
            return false;
        } 
        
        if (empty($memberID)) {
            JdbLog::warning('no memberID -risk windTips');
            return false;
        }
        
        $params = [
            'memberID' => $memberID,
            'ip' => IpUtil::getClientIp(),
            'type' => $type,
            'raw' => json_encode($raw)
        ];
        return ApiContext::get('risk','windTips',$params)->throwWhenFailed()->toArray();
    }
}
?>