<?php

namespace ploanframework\apis\handler;

/**
 * 处理对passport的请求参数和返回值
 * Class PassportHandler
 *
 * @package app\modules\datashop\apis
 * @author wangdj
 * @since 2017-03-06
 */
class PassportHandler extends DefaultHandler implements IRequestHandler{

    /**
     * @inheritdoc
     */
    public function getParams(){
        $params = $this->params;
        $params['app_id'] = $this->config['appId'];
        $params['app_key'] = $this->config['appKey'];
        return $params;
    }
}