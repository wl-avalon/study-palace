<?php
namespace rrxframework\service;
use rrxframework\base\JdbConf;
use rrxframework\base\JdbService;
use rrxframework\util\ServiceWfUtil;
use Yii;

class Base {
    static protected $serviceName = '';

    static protected $appId = '';

    static protected $appKey = '';

    static protected $secretKey = '';

    const ERR_FIELD_ERROR_NO = 'returnCode';

    const ERR_FIELD_ERR_MSG = 'returnMessage';

    const ERR_FIELD_ERR_USER_MSG = 'returnUserMessage';

    static public function getServiceName() {
        return static::$serviceName;
    }

    static public function call($urlPath, array &$param, &$result, $serviceName = ''
        , $requestMethod = JdbService::SERVICE_REQUEST_MODE_HTTP_POST, $modeConf = []) {
        static::$serviceName = empty($serviceName) ? static::$serviceName : $serviceName;

        if (!self::init()) {
            return false;
        }

        $param = static::formatParam($param);

        $result = JdbService::call(static::$serviceName, $urlPath, $param, $requestMethod, 'utf-8', 'json', false, $modeConf);

        if (empty($result)) {
            return false;
        }

        if (!static::checkFormat($result, $urlPath, $param)) {
            return false;
        }

        return true;
    }

    /**
     * 高级rpc call
     *
     * @param string $urlPath url地址
     * @param array $param 输入参数
     * @param array $error 返回的错误信息
     * @param mixed $data 返回的data信息
     * @param string $serviceName
     * @param int $successErrno 正确的errno
     * @param string $requestMethod
     * @return bool
     */
    static public function advancedCall($urlPath, array $param, &$error, &$data, $serviceName = ''
        , $successErrno = 0, $requestMethod = JdbService::SERVICE_REQUEST_MODE_HTTP_POST) {
        $status = self::call($urlPath, $param, $result, $serviceName, $requestMethod);

        if (!$status) {
            return false;
        }

        $error = [
            self::ERR_FIELD_ERROR_NO => $result['error']['returnCode'],
            self::ERR_FIELD_ERR_MSG => $result['error']['returnMessage'],
            self::ERR_FIELD_ERR_USER_MSG => $result['error']['returnUserMessage'],
        ];

        $error = $error;
        $data = $result['data'];

        if ($error[self::ERR_FIELD_ERROR_NO] != $successErrno) {
            ServiceWfUtil::errnoLog(static::$serviceName, $error[self::ERR_FIELD_ERROR_NO]
                , $error[self::ERR_FIELD_ERR_MSG], $urlPath, $param, json_encode($result));
            return false;
        }

        return true;
    }

    static public function multiCall($urlPath, array $param, &$result, $serverName = ''
        , $successErrno = 0, $requestMethod = JdbService::SERVICE_REQUEST_MODE_HTTP_M_POST) {
        static::$serviceName = empty($serviceName) ? static::$serviceName : $serviceName;

        if (!self::init()) {
            return false;
        }

        foreach ($param as $key => $value) {
            $param[$key] = static::formatParam($value);
        }

        $result = JdbService::call(static::$serviceName, $urlPath, $param, $requestMethod);

        if (empty($result)) {
            return false;
        }

        return true;
    }

    /**
     * 初始化
     * 校验服务配置,并对app id app key赋值
     *
     * @return bool
     */
    static protected function init() {
        $serviceConf = JdbConf::get(static::$serviceName);
        if(empty($serviceConf)) {
            ServiceWfUtil::localConfLog(static::$serviceName, ServiceWfUtil::LOCAL_CONF_ERROR_TYPE_EMPTY);

            return false;
        }

        $field = '';

        if (!isset($serviceConf['app_id'])) {
            $field = 'app_id';
        } else if (!isset($serviceConf['app_key'])) {
            $field = 'app_key';
        } else if (!isset($serviceConf['secret_key'])) {
            $field = 'secret_key';
        }

        if (!empty($field)) {
            ServiceWfUtil::localConfLog(static::$serviceName, ServiceWfUtil::LOCAL_CONF_ERROR_TYPE_LACK_FIELD, $field);

            return false;
        }

        static::$appId = $serviceConf['app_id'];
        static::$appKey = $serviceConf['app_key'];
        static::$secretKey = $serviceConf['secret_key'];

        return true;
    }

    static protected function formatParam($param) {
        return $param;
    }

    static protected function checkFormat($result, $urlPath, $formatParam) {
        return true;
    }

    /**
     * 从标准返回格式解析出错误信息
     *
     * @param $result
     * @return array
     */
    static protected function getErrorFromCommonResult($result) {
        $error = [
            self::ERR_FIELD_ERROR_NO => $result['error']['returnCode'],
            self::ERR_FIELD_ERR_MSG => $result['error']['returnMessage'],
            self::ERR_FIELD_ERR_USER_MSG => $result['error']['returnUserMessage'],
        ];

        return $error;
    }
}