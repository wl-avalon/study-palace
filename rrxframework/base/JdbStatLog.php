<?php
namespace rrxframework\base;

use Yii;
use rrxframework\base\JdbSession;
use rrxframework\util\IpUtil;

class JdbStatLog {

	// 日记格式前缀
    protected $_stat_format_prefix = "[{{time}}] [{{trackId}}] ";

    // 默认日记格式
    protected $_stat_format_default = "[{{userId}}] [{{operation}}] [{{status}}] [{{errMsg}}] [{{clientIP}}]";
    
    // 当前日记格式
    protected $_stat_format;
    
    // 日记格式后缀
    protected $_stat_format_suffix = "\n";
    
    // 日志文件
    protected $_log_file;
	
    protected $_log_file_default = 'jdbuser.log';
    
    static protected $instance;

    static private function getInstance() {
    	if (empty(self::$instance)) {
    		self::$instance = new JdbStatLog();
    	}
    
    	return self::$instance;
    }
    
    static public function setLogFile($file){
    	$obj = self::getInstance();
    	$obj->_log_file = $file;
    }
    
    static public function resetLogFile(){
    	$obj = self::getInstance();
    	$obj->_stat_format = $obj->_log_file_default;
    }
    
    
    static public function setStatFormat($format){
    	$obj = self::getInstance();
    	$obj->_stat_format = $format;
    }
    
    static public function resetStatFormat(){
    	$obj = self::getInstance();
    	$obj->_stat_format = $obj->_stat_format_default;
    }
    
    static private function formatKeys(array $fields){
    	$ret = [];
    	foreach($fields as $key => $value){
    		$ret['{{'.$key.'}}'] = $value;
    	}
    	return $ret;
    }
    
    static public function addStat($operation, $url, array $log_fields){
    	//日志字段转化
    	$log_fields = self::formatKeys($log_fields);
    	$log_fields['{{operation}}'] = $operation;
    	if(!isset($log_fields['{{time}}'])){
    		$cur_time = gettimeofday();
    		$mm_time = intval($cur_time['usec']/1000);
    		$log_fields['{{time}}'] = date("Y-m-d H:i:s:$mm_time", $cur_time['sec']);
    	}
    	
    	if(!isset($log_fields['{{trackId}}'])){
    		$log_fields['{{trackId}}'] = JdbSession::getSessionId() . ltrim($url, "/");
    	}
    	
    	if(!isset($log_fields['{{clientIP}}'])){
    		$log_fields['{{clientIP}}'] = IpUtil::getClientIp();
    	}
    	
    	$obj = self::getInstance();
    	$obj->writeLog($log_fields);
    	
    }
    
    

    private function writeLog(array $args) {

    	if(empty($this->_stat_format)){
    		$this->_stat_format = $this->_stat_format_default;
    	}
    	
    	if(empty($this->_log_file)){
    		$this->_log_file = $this->_log_file_default;
    	}
    	
    	$stat_format = $this->_stat_format_prefix . $this->_stat_format . $this->_stat_format_suffix;
    	
    	$log_str = strtr($stat_format, $args);
    	$log_str = preg_replace("/{{[0-9a-zA-z-_]+}}/", "", $log_str);
    	
    	
    	$path = Yii::getAlias("@app",false);
    	
    	if(strpos($this->_log_file, '/') === 0 || $path === false){
    		$log_file = $this->_log_file;
    	}else{
    		$log_file = sprintf('%s/../../logs/%s', $path, $this->_log_file);
    	}

        file_put_contents($log_file, $log_str, FILE_APPEND);
    }
}
