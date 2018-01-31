<?php

namespace ploanframework\apis\handler;

use ploanframework\apis\models\Response;
use ploanframework\constants\JdbErrors;

/**
 * 处理对鉴权的请求参数和返回值
 * Class AuthHandler
 * @package app\modules\wapui\apis
 */
class AuthHandler extends DefaultHandler implements IRequestHandler{
    /**
     * @inheritdoc
     */
    public function getParams(){
        $params = $this->params;
        unset($params['sign']);
        $params['app'] = $this->config['app'];

        $secret = $this->config['secret'];

        $params['sign'] = $this->genSign($params, $secret);
        return $params;
    }

    public function handleResponse(Response &$response, array $arrJson){
        $response->setReturnCode($arrJson['error']['returnCode']);
        $response->setReturnUserMessage($arrJson['error']['returnUserMessage']??JdbErrors::getUserMsg(JdbErrors::ERR_NO_UNKNOWN));
        $response->setReturnMessage($arrJson['error']['returnMessage']??'');
        $response->setData(['data' => $arrJson['data']??null, 'auc' => $arrJson['auc']??null]);

        return $response;
    }
}