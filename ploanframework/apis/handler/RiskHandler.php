<?php
/**
 * Created by PhpStorm
 * User: deling
 * Date: 2017/6/7
 * Time: 21:43
 */

namespace ploanframework\apis\handler;

use ploanframework\apis\models\Response;

class RiskHandler extends DefaultHandler{

    public function getParams(){
        $params = $this->params;
        $params['_ts'] = time();
        $params['ak'] = $this->config['ak'];
        $params['sign'] = $this->genSign($params, $this->config['secret']);
        $this->params = $params;
        return $params;
    }

    /**
     * @inheritdoc
     */
    public function handleResponse(Response &$response, array $arrJson){
        $response->setReturnCode($arrJson['errno']);
        $response->setReturnUserMessage($arrJson['errmsg']??'');
        $response->setReturnMessage($arrJson['errmsg']??'');
        if(isset($arrJson['data'])){
            $response->setData($arrJson['data']);
        }else{
            $response->setData($arrJson);
        }
        return $response;
    }
}