<?php

namespace ploanframework\components;

use rrxframework\base\JdbException;

class AuthException extends JdbException{
    /**
     * @var array
     */
    protected $authInfo;

    public function __construct($errNo, $errInfo = null, $authInfo = [], $errUserMsg = '', $errSysMsg = null){
        $this->err_info = $errInfo;
        $this->err_sys_msg = $errSysMsg;
        $this->authInfo = $authInfo;

        parent::__construct($errNo, $errInfo, $errUserMsg, $errSysMsg);
    }

    /**
     * 鉴权相关数据
     * @return array
     */
    public function getAuthInfo(){
        return $this->authInfo;
    }
}