<?php
namespace rrxframework\service;
use rrxframework\util\ServiceWfUtil;
use Yii;

class Idalloc extends Base {
    static protected $serviceName = 'idalloc';

    const GET_UUIDS_URL = '/innerApi/getUuids';

    const GET_UUID_URL = '/innerApi/getUuid';


    /**
     * 获取单个自增ID
     *
     * @param array $input [] 参数
     * @param array $error 返回的错误信息
     * @param mixed $data 返回的data部分
     * @return bool
     */
    static public function getUuid(array $input, &$error, &$data) {
        return self::advancedCall(self::GET_UUID_URL, $input, $error, $data, self::$serviceName);
    }

    /**
     * 获取批量自助ID
     *
     * @param array $input
     *      [
     *          'count' => 1, // 批量的个数
     *      ]
     * @param array $error 返回的错误信息
     * @param mixed $data 返回的data部分
     * @return bool
     */
    static public function getUuids(array $input, &$error, &$data) {
        return self::advancedCall(self::GET_UUIDS_URL, $input, $error, $data, self::$serviceName);
    }

    static protected function formatParam($param) {
        $param['appKey'] = self::$appKey;

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