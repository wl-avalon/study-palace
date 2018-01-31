<?php
namespace rrxframework\service;
use rrxframework\util\ServiceWfUtil;
use Yii;

class OpUI extends Base {
    static protected $serviceName = 'opui';

    const GET_LAYOUT_URL = '/mybankv21/phpopui/opui/inner/layout';
    const CHECK_WHITELIST = '/mybankv21/phpopui/opui/whitelist/check';

    /**
     * 获取页面布局信息
     *
     * @param array $input 输入参数
     *      [
     *          'memberID' => '', // 用户ID
     *          'nodeID' => '', // 页面节点ID
     *          'platform' => '', // 平台
     *          'channel' => '', // 渠道
     *          'version' => '', // 版本
     *      ]
     * @param array $error 返回的错误信息
     * @param mixed $data 返回的data部分
     * @return bool
     */
    static public function getLayout(array $input, &$error, &$data) {
        return self::advancedCall(self::GET_LAYOUT_URL, $input, $error, $data, self::$serviceName);
    }

    /**
     * 校验是否在白名单
     *
     * @param array $input 输入参数
     *      [
     *          'memberID' => '', // 用户ID
     *          'clientVersion' => '', // 端的版本信息(2.5.0)
     *          'channel' => '', // 渠道信息(dev,appstore,...)
     *          'platform' => '', // 渠道信息(dev,appstore,...)
     *          'funcList' => '', //功能列表（可传一个或多个，多个的情况以逗号分隔）
     *      ]
     * @param array $error 返回的错误信息
     * @param mixed $data 返回的data部分
     * @return bool
     */
    static public function checkWhitelist(array $input, &$error, &$data) {
        return self::advancedCall(self::CHECK_WHITELIST, $input, $error, $data, self::$serviceName);
    }

    static protected function formatParam($param) {
        $formatParam = $param;

        $formatParam['app'] = self::$appKey;
        $formatParam['ts'] = time();
        $formatParam['sign'] = self::genSign($formatParam);

        return $formatParam;
    }

    static protected function checkFormat($result, $urlPath, $formatParam) {
        $lackField = '';

        if (!isset($result['error'])) {
            $lackField = 'error';
        } else if (!isset($result['error']['returnCode'])) {
            $lackField = 'error.returnCode';
        } else if (!isset($result['data'])) {
            $lackField = 'data';
        }

        if (!empty($lackField)) {
            ServiceWfUtil::formatLog(self::$serviceName, ServiceWfUtil::FORMAT_ERROR_TYPE_LACK_FIELD, $lackField
                , $urlPath, $formatParam, json_encode($result));

            return false;
        }

        return true;
    }

    static protected function genSign($param){
        unset($param['sign']);
        ksort($param);
        $str = implode('|', $param);
        $str .= '|' . self::$secretKey;
        return md5($str);
    }
}