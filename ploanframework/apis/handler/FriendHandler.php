<?php

namespace ploanframework\apis\handler;

class FriendHandler extends DefaultHandler implements IRequestHandler{
    /**
     * @inheritdoc
     */
    public function getParams(){
        $params = $this->params;
        $params['_ts'] = time();
        $params['sign'] = $this->genSign($params, $this->config['secret']);
        $this->params = $params;
        return $params;
    }
}