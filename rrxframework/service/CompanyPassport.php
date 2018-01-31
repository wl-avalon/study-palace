<?php
namespace rrxframework\service;
use rrxframework\util\ServiceWfUtil;
use Yii;

class CompanyPassport extends Base {
    static protected $serviceName = 'company_passport';

    const GET_COMPANY_LIST_URL = '/mybankv21/qiyepassport/inner/company/get-company-list';

    static protected function formatParam($param) {
        $formatParam = $param;

        $formatParam['ts'] = time();
        $formatParam['app_id'] = self::$appKey;
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

    public static function createSign($arrData) {
        ksort($arrData);
        $strAppKey = self::$secretKey;
        $str = sprintf('%s|%s', implode('|', $arrData), $strAppKey);
        $sign = md5($str);
        return $sign;
    }
}