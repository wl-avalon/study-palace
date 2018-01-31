<?php
namespace rrxframework\base;

class JdbException extends \Exception {
    // 错误信息
    protected $err_info;
    protected $err_sys_msg;
    protected $deep;
    public function __construct($err_no, $err_info = null, $err_user_msg = '', $err_sys_msg = null, $deep = 0) {
        $this->err_info = $err_info;
		$this->err_sys_msg = $err_sys_msg;
		$this->deep = $deep;
		
        parent::__construct($err_user_msg, $err_no);
    }

    /**
    * 获取错误信息数据
    *
    * @return mix
    */
    public function getErrInfo() {
        return $this->err_info;
    }
    
    /**
     * 获取系统错误提示信息
     * @return string
     */
    public function getSysMessage(){
    	return $this->err_sys_msg;
    }
    
    public function getDeep(){
        return $this->deep;
    }
    
    public function getExpLine(){
        $index = $this->getDeep();
        if($index > 0){
            $trace = $this->getTrace();
            return $trace[$index - 1]['line'];
        }
       
        return $this->getLine();
    }
    
    public function getExpFile(){
        $index = $this->getDeep();
        if($index > 0){
            $trace = $this->getTrace();
            return $trace[$index - 1]['file'];
        }
       
        return $this->getFile();
    }
}