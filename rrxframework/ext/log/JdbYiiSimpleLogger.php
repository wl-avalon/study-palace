<?php
namespace rrxframework\ext\log;

use Yii;
use yii\base\Component;
use yii\log\Logger;
use rrxframework\base\JdbModule;
use rrxframework\base\JdbLog;


/*
 * 精简版的yii日志组件，因为精简，所以与yii的debug等日志扩展不兼容
 * 配置方法如下：
'log' => [
	'class' => 'app\rrx\ext\log\JdbYiiSimpleLogger',
	'log_level' =>  16,
	'log_path' => dirname(dirname(__DIR__)).'/logs',
	'default_module' => 'index',
    'module_map' => [
    	'passport' => 'user',
    ],
],
*/

class JdbYiiSimpleLogger extends Component{
    const LOG_LEVEL_FATAL   = 0x01;
    const LOG_LEVEL_WARNING = 0x02;
    const LOG_LEVEL_NOTICE  = 0x04;
    const LOG_LEVEL_TRACE   = 0x08;
    const LOG_LEVEL_DEBUG   = 0x10;

    static public $yii_log_level_map = array(
    	Logger::LEVEL_WARNING => self::LOG_LEVEL_WARNING,
    	Logger::LEVEL_ERROR => self::LOG_LEVEL_FATAL,
    	Logger::LEVEL_INFO => self::LOG_LEVEL_DEBUG,
    	Logger::LEVEL_TRACE => self::LOG_LEVEL_TRACE,
    	Logger::LEVEL_PROFILE => self::LOG_LEVEL_TRACE,
    	Logger::LEVEL_PROFILE_BEGIN => self::LOG_LEVEL_TRACE,
    	Logger::LEVEL_PROFILE_END => self::LOG_LEVEL_TRACE,
    );
    
    static public $log_level_map = array(
        self::LOG_LEVEL_FATAL   => 'FATAL',
        self::LOG_LEVEL_WARNING => 'WARNING',
        self::LOG_LEVEL_NOTICE  => 'NOTICE',
        self::LOG_LEVEL_TRACE   => 'TRACE',
        self::LOG_LEVEL_DEBUG   => 'DEBUG',
    );
    
    static public $log_suffix_map = array(
    		self::LOG_LEVEL_FATAL   => '.wf',
    		self::LOG_LEVEL_WARNING => '.wf',
    		self::LOG_LEVEL_NOTICE  => '',
    		self::LOG_LEVEL_TRACE   => '',
    		self::LOG_LEVEL_DEBUG   => '',
    );
    
    const DEFAULT_FORMAT_DEBUG = "%s: %s %s [%s:%s] errno[%s] log_id[%s] %s %s\n";
	const DEFAULT_LOG_LEVEL = 16;
	const DEFAULT_MODULE = 'default';
	const URI_SEPATATOR = '/';
	
    // 配置的日志级别
    public $log_level;
    public $log_path;
    public $default_module;
    public $module_map;
    protected $module_name;
    
    protected $add_notice_data;


    public function __construct($config = []) {
        parent::__construct($config);
    }


    public function init()
    {
    	parent::init();
    	
    	$this->log_path = empty($this->log_path) ? Yii::$app->getRuntimePath()."/logs" : $this->log_path;
    	$this->log_level = empty($this->log_level) ? self::DEFAULT_LOG_LEVEL : $this->log_level;
    	$this->add_notice_data = [];
    	
    	$this->startTimer('TOTAL');
    	JdbModule::setModuleName($this->getModuleName());
    	Yii::setLogger($this);
    	JdbLog::setLogger($this);
    	
    	
    	register_shutdown_function(function () {
    		$this->notice();
    	});
    }
    
    public function getModuleName(){
    	if($this->module_name !== null){
    		return $this->module_name;
    	}
    	 
    	if(Yii::$app instanceof yii\console\Application){
    		$request_uri = ltrim($_SERVER['argv'][1], self::URI_SEPATATOR);
    	}else{
    		$request_uri = ltrim($_SERVER['REQUEST_URI'], self::URI_SEPATATOR);
    	}
    	
    	$pos = strpos($request_uri, self::URI_SEPATATOR);
    	if($pos === false){
    		$module_name = $this->default_module === null ? self::DEFAULT_MODULE : $this->default_module;
    	}else{
    		$module_name = substr($request_uri, 0, $pos);
    	}
    	 
    	if(!empty($this->module_map[$module_name])){
    		$module_name = $this->module_map[$module_name];
    	}
    	 
    	$this->module_name = $module_name;
    	return $this->module_name;
    }
    
    public function startTimer($timer_name,$renew = false){
    	if($renew || !isset($this->timer_data[$timer_name]['start_time'])){
    		$this->timer_data[$timer_name] = [];
    		$this->timer_data[$timer_name]['start_time'] = $this->getMilliTime();
    	}
    }
    
    public function endTimer($timer_name){
    	if(isset($this->timer_data[$timer_name]['start_time']) && !isset($this->timer_data[$timer_name]['interval'])){
    		$this->timer_data[$timer_name]['end_time'] = $this->getMilliTime();
    		$this->timer_data[$timer_name]['interval'] = $this->timer_data[$timer_name]['end_time']
    		- $this->timer_data[$timer_name]['start_time'];
    	}
    }
    
    public function getTimer($timer_name = null){
    	if(isset($this->timer_data[$timer_name])){
    		return $this->timer_data[$timer_name];
    	}else if($timer_name === null){
    		return $this->timer_data;
    	}
    	return array();
    }
    
    public function calcTimers($timer_prefix = ''){
    	$end_time = $this->getMilliTime();
    	$ret_timers = [];
    	foreach($this->timer_data as $timer => &$timer_value){
    		if(!isset($timer_value['interval'])){
    			$timer_value['end_time'] = $end_time;
    			$timer_value['interval'] = $end_time - $timer_value['start_time'];
    		}
    
    		$ret_timers[$timer_prefix.$timer] = $timer_value['interval'];
    	}
    	return $ret_timers;
    }
    
    public function getMilliTime(){
    	return intval(gettimeofday(true) * 1000);
    }
    
    public function addNotice($key, $value) {
        if (!isset($this->add_notice_data[$key])) {
            $this->add_notice_data[$key] = $value;
        }
    }

    public function log($message, $level, $category = null, $errno = 0, $args = null)
    {
    	$this->writeLog(self::$yii_log_level_map[$level], $message, $errno, $args, 2);
    }

    public function debug($str = '', $errno = 0, $args = null, $depth = 0) {
        $this->writeLog(self::LOG_LEVEL_DEBUG, $str, $errno, $args, $depth + 1);
    }

    public function trace($str = '', $errno = 0, $args = null, $depth = 0) {
        $this->writeLog(self::LOG_LEVEL_TRACE, $str, $errno, $args, $depth + 1);
    }

    public function notice($str = '', $errno = 0, $args = null, $depth = 0) {
    	$timers = $this->calcTimers('TIMER_');
    	$args = is_array($args) ? array_merge($args, $this->add_notice_data, $timers) : array_merge($this->add_notice_data, $timers);
		$this->writeLog(self::LOG_LEVEL_NOTICE, $str, $errno, $args, $depth + 1);
    }

    public function warning($str = '', $errno = 0, $args = null, $depth = 0) {
        $this->writeLog(self::LOG_LEVEL_WARNING, $str, $errno, $args, $depth + 1);
    }

    public function fatal($str = '', $errno = 0, $args = null, $depth = 0) {
        $this->writeLog(self::LOG_LEVEL_FATAL, $str, $errno, $args, $depth + 1);
    }

    static public function genLogID() {
        if (defined('LOG_ID')) {
            return LOG_ID;
        }

        $arr = gettimeofday();
        $log_id = ((($arr['sec'] * 100000 + $arr['usec'] / 10) & 0x7FFFFFFF) | 0x80000000);

        define('LOG_ID', $log_id);

        return LOG_ID;
    }

    protected function writeLog($log_level, $str, $errno, $args, $depth) {
        if (($log_level > $this->log_level) || !isset(self::$log_level_map[$this->log_level])) {
            return;
        }

        $module_name = $this->getModuleName();
        $log_dir_path = $this->log_path . "/" .  $module_name;
        if(!is_dir($log_dir_path)){
        	mkdir($log_dir_path, 0766, true);
        }
        $log_file = $log_dir_path . "/" . $module_name . ".log";
        
        $log_level_str = self::$log_level_map[$log_level];
        $time_str = date('y-m-d H:i:s');
        
        if(Yii::$app instanceof yii\web\Application){
        	$client_ip = Yii::$app->request->getUserIP();
        }else{
        	$client_ip = '127.0.0.1';
        }

        $trace = debug_backtrace();
        $trace_file = isset($trace[$depth]['file']) ? $trace[$depth]['file'] : '';
        $trace_line = isset($trace[$depth]['line']) ? $trace[$depth]['line'] : '';
        $log_id = self::genLogID();

        $args_str = '';
        if (is_array($args) && !empty($args)) {
            foreach ($args as $arg_key => $arg_value) {
                $arg_value_str = is_array($arg_value) ? json_encode($arg_value) : $arg_value;
                $args_str .= "${arg_key}[${arg_value_str}] ";
            }
        }

        $str = sprintf(self::DEFAULT_FORMAT_DEBUG
            , $log_level_str, $time_str, $client_ip, $trace_file, $trace_line, $errno, $log_id, $str, $args_str);

        $file_name_suffix = self::getLogSuffixByLevel($log_level);
        $log_file = $log_file . $file_name_suffix;
        file_put_contents($log_file, $str, FILE_APPEND);
    }
    
    static public function getLogSuffixByLevel($log_level){
    	return self::$log_suffix_map[$log_level];
    }
}
