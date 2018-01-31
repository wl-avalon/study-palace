<?php
namespace rrxframework\service;
use rrxframework\util\ServiceWfUtil;
use Yii;

class Friend extends Base {
    static protected $serviceName = 'friend';

    const GET_FOLLOW_RELATION_LIST = '/mybankv21/phpfriend/friend/inner/getFollowRelationList';

    static public function getFollowRelationList(array $input, &$error, &$data) {
        return self::advancedCall(self::GET_FOLLOW_RELATION_LIST, $input, $error, $data, self::$serviceName);
    }

    static protected function formatParam($param) {
        $param['_ts'] = time();

        ksort($param);
        $str = implode('|', $param);
        $str .= '|' . self::$secretKey;

        $param['sign'] = md5($str);

        return $param;
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
}