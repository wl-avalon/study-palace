<?php
namespace rrxframework\service;
use rrxframework\util\ServiceWfUtil;
use Yii;

class Contract extends Base {
    static protected $serviceName = 'contract';

    const CREATE_NEW_CONTRACT_PDF_URL = '/contract/new/createNewContractPDF';

    /**
     * 获取页面布局信息
     *
     * @param array $input 输入参数
     *      [
     *          'contractNumber' => '', // 协议编号
     *          'contractTemplateCode' => '', // 模板编号
     *          'contractTemplateData' => '', // json_encode后的模板变量
     *      ]
     * @param array $error 返回的错误信息
     * @param mixed $data 返回的data部分
     * @return bool
     */
    static public function createNewContractPDF(array $input, &$error, &$data) {
        return self::advancedCall(self::CREATE_NEW_CONTRACT_PDF_URL, $input, $error, $data, self::$serviceName);
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