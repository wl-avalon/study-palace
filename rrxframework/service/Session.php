<?php
namespace rrxframework\service;
use rrxframework\util\ServiceWfUtil;
use Yii;

class Session extends Base {
    static protected $serviceName = 'session';

    const CHECK_LOGIN_URL = '/mybankv21/member/checkLogin';

    /**
     * 校验登陆态
     *
     * @param array $input 输入参数
     *      [
     *          'memberID' => '', // 用户ID
     *          'udid' => '', // 设备ID
     *          'accessToken' => '', // 登陆token
     *      ]
     * @param array $error 返回的错误信息
     * @param mixed $data 返回的data部分
     * @return bool
     */
    static public function checkLogin(array $input, &$error, &$data) {
        return self::advancedCall(self::CHECK_LOGIN_URL, $input, $error, $data, self::$serviceName);
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