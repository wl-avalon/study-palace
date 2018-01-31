<?php

namespace ploanframework\apis\handler;

use ploanframework\apis\models\Response;
use ploanframework\constants\JdbErrors;

class DwzHandler extends DefaultHandler implements IRequestHandler{
    public function handleResponse(Response &$response, array $arrJson){
        $response->setReturnCode(JdbErrors::ERR_NO_SUCCESS);
        $response->setData($arrJson);
        return $response;
    }
}