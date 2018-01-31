<?php
namespace rrxframework\service;
use rrxframework\base\JdbLog;
use rrxframework\util\ServiceWfUtil;
use Yii;

class PaygentNew extends Base {
    static protected $serviceName = 'paygent_new';

    static protected function formatParam($param) {
        $formatParam = $param;

        $formatParam['appId'] = self::$appId;
        $formatParam['traceId'] = JdbLog::getLogID();
        $formatParam['sign'] = self::genSign($formatParam);

        return $formatParam;
    }

    static protected function genSign(array $param) {
        if(empty($param)) {
            return false;
        }

        ksort($param);
        $values = array_values($param);
        $str = self::$secretKey . '|' . implode('|', $values);
        $sign = md5($str);

        JdbLog::debug(sprintf('paygent str[%s] sign[%s]', $str, $sign));

        return $sign;
    }

    static protected function checkFormat($result, $urlPath, $formatParam) {
        $lackField = '';
        if (!isset($result['error'])) {
            $lackField = 'error';
        } else if (!isset($result['data'])) {
            $lackField = 'data';
        } else if (!isset($result['error']['result'])) {
            $lackField = 'error.result';
        }
        if (!empty($lackField)) {
            ServiceWfUtil::formatLog(self::$serviceName, ServiceWfUtil::FORMAT_ERROR_TYPE_LACK_FIELD, $lackField
                , $urlPath, $formatParam, json_encode($result));

            return false;
        }

        return true;
    }
}