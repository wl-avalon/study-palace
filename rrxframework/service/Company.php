<?php
namespace rrxframework\service;
use rrxframework\util\ServiceWfUtil;
use Yii;

class Company extends Base {
    static protected $serviceName = 'company';

    static protected function formatParam($param) {
        $formatParam = $param;

        $formatParam['ts'] = time();
        $formatParam['appid'] = self::$appKey;
        $formatParam['sign'] = self::createSign($formatParam);

        return $formatParam;
    }

    static protected function checkFormat($result, $urlPath, $formatParam) {
        $lackField = '';

        if (!isset($result['error'])) {
            $lackField = 'error';
        } else if (!isset($result['error']['returnCode'])) {
            $lackField = 'error.returnCode';
        }

        if (!empty($lackField)) {
            ServiceWfUtil::formatLog(self::$serviceName, ServiceWfUtil::FORMAT_ERROR_TYPE_LACK_FIELD, $lackField
                , $urlPath, $formatParam, json_encode($result));

            return false;
        }

        return true;
    }

    static protected function createSign($data) {
        ksort($data);

        $tmp = [];

        foreach ($data as $key => $value) {
            $tmp[] = sprintf('%s=%s', $key, urlencode($value));
        }

        $str = implode('&', $tmp);
        $str .= '&appkey=' . self::$secretKey;

        $sign = md5($str);

        return $sign;
    }
}