<?php

namespace ploanframework\apis\handler;

use ploanframework\apis\models\Response;
use ploanframework\constants\JdbErrors;
use rrxframework\util\StrUtil;

/**
 * 处理对支付的请求参数和返回值
 * Class AuthHandler
 *
 * @package app\modules\datashop\apis
 * @author wangdj
 * @since 2017-03-06
 */
class PaygentHandler extends DefaultHandler implements IRequestHandler{
    /**
     * @inheritdoc
     */
    public function genSign(array $fields, $secret){
        ksort($fields);
        $str = $secret . '|' . implode('|', array_values($fields));
        return md5($str);
    }

    /**
     * @inheritdoc
     */
    public function getParams(){
        $secret = $this->config['secret'];
        $params = $this->params;
        $params['appId'] = $this->config['appId'];
        $params['traceId'] = StrUtil::getLogId();
        $params['ts'] = time();
        $params['sign'] = $this->genSign($params, $secret);
        return $params;
    }

    /**
     * @inheritdoc
     */
    public function handleResponse(Response &$response, array $arrJson){
        $message = "操作失败，请稍后重试！";
        if(!isset($arrJson['error']) || !isset($arrJson['error']['result']) || !isset($arrJson['error']['returnCode'])){
            $code = JdbErrors::ERR_NO_SERVER_BUSY;
        }elseif(!isset($arrJson['data'])){
            $code = JdbErrors::ERR_NO_SERVER_BUSY;
        }else{
            // 1表示成功，2表示失败，3进行中，4表示未知
            $result = $arrJson['error']['result'];

            if($result == 1){
                $code = JdbErrors::ERR_NO_SUCCESS;
                $message = '获取数据成功';
                $response->setData($arrJson['data']);
            }else{
                $code = JdbErrors::ERR_NO_SERVER_BUSY;
                $message = $arrJson['error']['returnUserMessage']??"操作失败，请稍后重试！";
            }
        }

        $response->setReturnCode($code);
        $response->setReturnUserMessage($message);
        $response->setReturnMessage($arrJson['error']['returnMessage']??'');

        return $response;
    }
}