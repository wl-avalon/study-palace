<?php

namespace ploanframework\apis\handler;

use rrxframework\base\JdbLog;
use rrxframework\base\JdbModule;

class SmsHandler extends DefaultHandler implements IRequestHandler{
    public function getParams(){
        $params = $this->params;
        $params['username'] = $this->config['username'];
        $params['password'] = $this->config['password'];
        $params['traceId'] = JdbModule::getModuleName() . '-' . JdbLog::getLogID();

        return $params;
    }
}