<?php
namespace rrxframework\service;
use rrxframework\base\JdbLog;
use rrxframework\util\ServiceWfUtil;
use Yii;

class Datagent extends Base {
    static protected $serviceName = 'datagent';

    static protected function formatParam($param) {
        $formatParam = $param;

        $formatParam['ak'] = self::$appKey;
        $formatParam['traceId'] = JdbLog::getLogID();
        $formatParam['sign'] = self::genSign($formatParam);

        return $formatParam;
    }

    static protected function checkFormat($result, $urlPath, $formatParam) {
        $lackField = '';

        if (!isset($result['errno'])) {
            $lackField = 'errno';
        } else if (!isset($result['errmsg'])) {
            $lackField = 'errmsg';
        }

        if (!empty($lackField)) {
            ServiceWfUtil::formatLog(self::$serviceName, ServiceWfUtil::FORMAT_ERROR_TYPE_LACK_FIELD, $lackField
                , $urlPath, $formatParam, json_encode($result));

            return false;
        }

        return true;
    }

    static protected function genSign($param){
        ksort($param);
        $str = implode('|', $param);
        $str .= '|' . self::$secretKey;
        return md5($str);
    }
}