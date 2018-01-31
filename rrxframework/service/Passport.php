<?php
namespace rrxframework\service;
use rrxframework\util\ServiceWfUtil;
use Yii;

class Passport extends Base {
    static protected $serviceName = 'passport';

    const GET_UINFO_URL = '/mybankv21/phppassport/v2/passport/inner/getuinfo';

    const GET_ULIST_URL = '/mybankv21/phppassport/v2/passport/inner/getulist';

    const GET_UINFO_BY_PHONENUM_URL = '/mybankv21/phppassport/v2/passport/inner/getuinfobyphonenum';

    const GET_ULIST_BY_PHONENUM_URL = '/mybankv21/phppassport/v2/passport/inner/getulistbyphonenum';


    /**
     * 获取用户信息
     *
     * @param array $input 输入参数
     *      [
     *          'user_id' => '', // 用户ID
     *          'fields' => '', // 所需字段,非必须 http://api.jdb-dev.com/passportapi/inner_uinfo_fields.html
     *          'screen_width' => '', // 屏幕宽度,非必须
     *      ]
     * @param array $error 返回的错误信息
     * @param mixed $data 返回的data部分
     * @return bool
     */
    static public function getUserInfo(array $input, &$error, &$data) {
        return self::advancedCall(self::GET_UINFO_URL, $input, $error, $data, self::$serviceName);
    }

    /**
     * 批量获取用户信息
     *
     * @param array $input 输入参数
     *      [
     *          'user_id_list' => '', // 用户ID列表
     *          'fields' => '', // 所需字段,非必须 http://api.jdb-dev.com/passportapi/inner_uinfo_fields.html
     *          'screen_width' => '', // 屏幕宽度,非必须
     *          'result_type' => 1, // 结果类型,非必须
     *      ]
     * @param array $error 返回的错误信息
     * @param mixed $data 返回的data部分
     * @return bool
     */
    static public function getUserList(array $input, &$error, &$data) {
        return self::advancedCall(self::GET_ULIST_URL, $input, $error, $data, self::$serviceName);
    }

    /**
     * 根据手机号获取用户信息
     *
     * @param array $input 输入参数
     *      [
     *          'phone_num' => '', // 用户ID
     *          'fields' => '', // 所需字段,非必须 http://api.jdb-dev.com/passportapi/inner_uinfo_fields.html
     *          'screen_width' => '', // 屏幕宽度,非必须
     *      ]
     * @param array $error 返回的错误信息
     * @param mixed $data 返回的data部分
     * @return bool
     */
    static public function getUserInfoByPhoneNum(array $input, &$error, &$data) {
        return self::advancedCall(self::GET_UINFO_BY_PHONENUM_URL, $input, $error, $data, self::$serviceName);
    }

    /**
     * 根据手机号批量获取用户信息
     *
     * @param array $input 输入参数
     *      [
     *          'phone_num_list' => '', // 用户ID
     *          'fields' => '', // 所需字段,非必须 http://api.jdb-dev.com/passportapi/inner_uinfo_fields.html
     *          'screen_width' => '', // 屏幕宽度,非必须
     *          'result_type' => 1, // 结果类型,非必须
     *      ]
     * @param array $error 返回的错误信息
     * @param mixed $data 返回的data部分
     * @return bool
     */
    static public function getUserListByPhoneNum(array $input, &$error, &$data) {
        return self::advancedCall(self::GET_ULIST_BY_PHONENUM_URL, $input, $error, $data, self::$serviceName);
    }

    static protected function formatParam($param) {
        $param['app_id'] = self::$appId;
        $param['app_key'] = self::$appKey;

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