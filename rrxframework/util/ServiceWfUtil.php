<?php
namespace rrxframework\util;
use Yii;

use rrxframework\base\JdbLog;
use rrxframework\base\JdbRpcLog;
use rrxframework\util\RpcInfoUtil;
/**
 * 外部服务交互错误日志
 *
 * Class ServiceWfUtil
 * @package rrxframework\util
 */
class ServiceWfUtil {
    // 错误类型
    // 网络交互异常
    const ERROR_TYPE_NET = -1;
    // 格式非法
    const ERROR_TYPE_FORMAT = -2;
    // 返回码异常
    const ERROR_TYPE_RET_CODE = -3;
    // 本地配置异常
    const ERROR_TYPE_LOCAL_CONF = -4;

    // 错误级别 warn
    const ERR_LEVEL_WARN = 'warn';
    // 错误级别 fatal
    const ERR_LEVEL_FATAL = 'fatal';

    static protected $errorTypeMap = [
        self::ERROR_TYPE_NET => 'net',
        self::ERROR_TYPE_FORMAT => 'format',
        self::ERROR_TYPE_RET_CODE => 'ret_code',
        self::ERROR_TYPE_LOCAL_CONF => 'local_conf',
    ];

    // 格式错误类型
    // 解包问题
    const FORMAT_ERROR_TYPE_UNPACK = -2001;
    // 字段缺失
    const FORMAT_ERROR_TYPE_LACK_FIELD = -2002;
    // 数据类型问题
    const FORMAT_ERROR_TYPE_DATA_TYPE = -2003;

    static protected $formatErrorTypeMap = [
        self::FORMAT_ERROR_TYPE_UNPACK => 'unpack',
        self::FORMAT_ERROR_TYPE_LACK_FIELD => 'lack_field',
        self::FORMAT_ERROR_TYPE_DATA_TYPE => 'data_type',
    ];

    // 本地配置错误类型
    // 配置缺失
    const LOCAL_CONF_ERROR_TYPE_EMPTY = -4001;
    // 字段缺失
    const LOCAL_CONF_ERROR_TYPE_LACK_FIELD = -4002;
    // 参数为空
    const LOCAL_CONF_ERROR_TYPE_PARAM_EMPTY = -4003;

    static protected $localConfErrorTypeMap = [
        self::LOCAL_CONF_ERROR_TYPE_EMPTY => 'empty',
        self::LOCAL_CONF_ERROR_TYPE_LACK_FIELD => 'lack_field',
        self::LOCAL_CONF_ERROR_TYPE_PARAM_EMPTY => 'param_empty',
    ];

    /**
     * 网络交互异常日志
     *
     * @param $serviceName string 服务名
     * @param $curlErrno int curl错误码
     * @param $curlErrmsg string curl错误信息
     * @param $requestUri string 请求URL
     * @param $requestParam array 请求参数
     * @param $responseBody string 返回内容
     * @param $extInfo array 扩展信息
     */
    static public function netLog($serviceName, $curlErrno, $curlErrmsg, $requestUri
        , array $requestParam, $responseBody, array $extInfo = []) {
        $curlErrmsg = sprintf('%s::%s', self::$errorTypeMap[self::ERROR_TYPE_NET], $curlErrmsg);

        self::log($serviceName, self::ERROR_TYPE_NET, $curlErrno, $curlErrmsg, $requestUri
            , $requestParam, $responseBody, $extInfo);
    }

    /**
     * 格式错误日志
     *
     * @param $serviceName string 服务名
     * @param $formatErrorType int 格式错误类型
     * @param $formatErrmsg string 格式错误详细信息,如具体协议json,具体缺失的字段error.returnCode等
     * @param $requestUri string 请求URL
     * @param $requestParam array 请求参数
     * @param $responseBody string 返回内容
     * @param $extInfo array 扩展信息
     */
    static public function formatLog($serviceName, $formatErrorType, $formatErrmsg, $requestUri
        , array $requestParam, $responseBody, array $extInfo = []) {
        $formatErrorTypeMsg = isset(self::$formatErrorTypeMap[$formatErrorType])
            ? self::$formatErrorTypeMap[$formatErrorType] : 'unkown';
        $formatErrmsg = sprintf('%s:%s:%s', self::$errorTypeMap[self::ERROR_TYPE_FORMAT]
            , $formatErrorTypeMsg, $formatErrmsg);

        self::log($serviceName, self::ERROR_TYPE_FORMAT, $formatErrorType, $formatErrmsg, $requestUri
            , $requestParam, $responseBody, $extInfo);
    }

    /**
     * 返回码异常日志
     *
     * @param $serviceName string 服务名
     * @param $serviceRetCode string|int 服务返回码
     * @param $serviceRetMsg string 服务返回信息
     * @param $requestUri string 请求URL
     * @param $requestParam array 请求参数
     * @param $responseBody string 返回内容
     * @param $extInfo array 扩展信息
     */
    static public function errnoLog($serviceName, $serviceRetCode, $serviceRetMsg, $requestUri
        , array $requestParam, $responseBody, array $extInfo = []) {
        $serviceRetMsg = sprintf('%s::%s', self::$errorTypeMap[self::ERROR_TYPE_RET_CODE]
            , $serviceRetMsg);

        self::log($serviceName, self::ERROR_TYPE_RET_CODE, $serviceRetCode, $serviceRetMsg, $requestUri
            , $requestParam, $responseBody, $extInfo);
    }

    /**
     * 本地配置错误日志
     *
     * @param $serviceName
     * @param $localConfErrorType
     * @param string $localConfErrmsg
     * @param array $extInfo
     */
    static public function localConfLog($serviceName, $localConfErrorType, $localConfErrmsg = '', array $extInfo = []) {
        $localConfErrorTypeMsg = isset(self::$localConfErrorTypeMap[$localConfErrorType])
            ? self::$localConfErrorTypeMap[$localConfErrorType] : 'unkown';
        $localConfErrmsg = sprintf('%s:%s:%s', self::$errorTypeMap[self::ERROR_TYPE_LOCAL_CONF]
            , $localConfErrorTypeMsg, $localConfErrmsg);

        self::log($serviceName, self::ERROR_TYPE_LOCAL_CONF, $localConfErrorType, $localConfErrmsg, '', [], '', $extInfo);
    }

    /**
     * @param $serviceName
     * @param $errorType
     * @param $serviceErrno
     * @param $serviceErrmsg
     * @param $requestUri
     * @param array $requestParam
     * @param $responseBody
     * @param array $extInfo
     */
    static protected function log($serviceName, $errorType, $serviceErrno, $serviceErrmsg
        , $requestUri, array $requestParam, $responseBody, array $extInfo = []) {
        $errorLevel = isset($extInfo['error_level']) && ($extInfo['error_level'] == self::ERR_LEVEL_WARN)
            ? self::ERR_LEVEL_WARN : self::ERR_LEVEL_FATAL;

        $errorInfo = [
            'error_level' => $errorLevel,
            'errmsg' => 'service_error',
            'service_name' => $serviceName,
            'error_type' => $errorType,
            'service_errno' => $serviceErrno,
            'service_errmsg' => $serviceErrmsg,
            'request_uri' => $requestUri,
            'request_param' => http_build_query($requestParam),
            'response_body' => str_replace(["\r\n", "\n", "\r"], '', $responseBody),
            'ext_info' => json_encode($extInfo),
        ];

        JdbLog::warning('', $errorType, $errorInfo, 2);
    }

    /**
    * @param $serviceName
    * @param $intErrno
    * @param $strUrl
    * @param $resContent
    * @param $csStartTime
    * @param $csEndTime
    */
    static public function rpcLog($serviceName, $intErrno, $strUrl, $resContent, $csStartTime, $csEndTime, $rpcId){
      $arrRpc = array();
      //$arrRpc['interfacename'] = $strUrl;
      $arrRpc['cost']          = intval(1000*($csEndTime-$csStartTime));

      $arrUrlInfo = parse_url($strUrl);
      $arrRpc['interfacename'] = isset($arrUrlInfo['path']) ? $arrUrlInfo['path'] : "/";
      $arrRpc['remoteip']      = isset($arrUrlInfo['host']) ? $arrUrlInfo['host'] : 'unknow';
      $arrRpc['remoteport']    = isset($arrUrlInfo['port']) ? $arrUrlInfo['port'] : 80;
      $arrRpc['annotationlist'][] = array('timestamp' => floor($csStartTime*1000), 'value' => 'cs');
      $arrRpc['annotationlist'][] = array('timestamp' => floor($csEndTime*1000), 'value' => 'cr');
      $arrRpc['rpcid']    = $rpcId;
      if($intErrno == 0){
        $tempRes = json_decode($resContent, true);
        if(isset($tempRes['error']['returnCode'])){
          $arrRpc['result'] = $tempRes['error']['returnCode'];
        }else{
          //非标准返回包，返回S0，用于正常包区分
          $arrRpc['result'] = "S0";
        }
      }else{
        //系统错误码同业务错误码区分
        $arrRpc['result'] = $intErrno * (-1);
      }

      jdbRpcLog::rpcNotice($arrRpc, 1, 0);
    }

    /**
    * @param $intErrno
    * @param $ssStartTime
    * @param $ssEndTime
    */
    static public function noticeLog($intErrno, $ssStartTime, $ssEndTime, $businessType = 0, $serviceID = '', $transactionID = '', $memberID = '', $amount = 0){
      $arrRpc = array();
      //var_dump($_SERVER);
      $arrRpc['interfacename'] = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : 'default';
      $arrRpc['cost']          = intval(1000*($ssEndTime - $ssStartTime));
      $arrRpc['remoteip']      = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : 'unknow';
      $arrRpc['remoteport']    = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80;
      $arrRpc['annotationlist'][] = array('timestamp' => floor($ssEndTime*1000), 'value' => 'ss');
      $arrRpc['annotationlist'][] = array('timestamp' => floor($ssStartTime*1000), 'value' => 'sr');
      $arrRpc['result'] = $intErrno;
      $arrRpc['rpcid']  = RpcInfoUtil::getServerRpcId();
      $arrRpc['binaryAnnotationslist'] = [
          'amount'  => $amount,
          'memberID' => $memberID,
          'type' => $businessType,
          'serviceID' => $serviceID,
          'transactionID' => $transactionID
      ];
      jdbRpcLog::rpcNotice($arrRpc, 1, 1);      
    }
}