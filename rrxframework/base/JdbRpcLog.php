<?php
namespace rrxframework\base;

use Yii;
use rrxframework\base\JdbSession;
use rrxframework\util\IpUtil;
use rrxframework\base\JdbLog;

//相关格式参考：http://wiki.jdb-dev.com/pages/viewpage.action?pageId=22288671
class JdbRpcLog {

    // 默认日记格式
    protected $statFormat = "%s◊%s◊%s◊%s\n";
    
    //logtype
    protected $logtype = "trace";

    //level
    protected $level = "info";

    //version
    protected $version = "0.1";

    //requesttype
    // Duboo  (0, "dubbo请求"),Http  (1, "http请求"),AntMQ (2, "antMQ请求");
    protected $requesttype = 1;

    protected $pid = "pid-0";

    protected $logDir = '/data/logs/rpc';
    protected $logFile = '/data/logs/rpc/jdbrpc.log';
    
    protected $buf="";

    static protected $instance;

    static private function getInstance() {
      if (empty(self::$instance)) {
        self::$instance = new JdbRpcLog();
      } 
      return self::$instance;
    }
    
    static public function setLogFile($file){
      $obj = self::getInstance();
      $obj->logFile = $file;
    }
        
    static public function setStatFormat($format){
      $obj = self::getInstance();
      $obj->statFormat = $format;
    }

    /*
    traceid：32位随机字符串，[0-9a-z]
    cost：耗时
    rpcid: 形如：0.1.1，用来表示层级关系
    timestamp：日志记录时间，精确到秒
    interfacename：请求api
    logtype：日志类型，暂时只有一个值，默认为trace
    level：trace日志级别，默认为info，后续将该字段作为trace日志级别开关
    version：trace数据版本，根据迭代版本进行更新，初始版本为0.1
    result: 返回结果，类似http状态码
    remoteip：对端服务器的ip
    remoteport：对端服务器端口，只有cs和cr的时候，才输出对端服务器端口号
    requesttype：请求类型， Duboo  (0, "dubbo请求"),Http  (1, "http请求"),AntMQ (2, "antMQ请求");
    annotationlist: trace相关注解
    */

    static public function rpcNotice($rpcData, $type, $noticeType){
      $obj = self::getInstance();

      //日志字段转化
      $logFields = array();
      $logFields['traceid'] = strval(JdbLog::getLogID());

      $binaryannotationlist = [];
      if (!empty($rpcData['binaryAnnotationslist'])) {
          foreach ($rpcData['binaryAnnotationslist'] as $key => $val) {
              $binaryannotationlist[] = ['key' => $key, 'value' => $val];
          }
      }
      $logFields['cost'] = $rpcData['cost'];
      $logFields['interfacename'] = $rpcData['interfacename'];
      $logFields['result'] = intval($rpcData['result']);
      $logFields['remoteip'] = $rpcData['remoteip'];
      $logFields['remoteport'] = $rpcData['remoteport'];
      $logFields['annotationlist'] = $rpcData['annotationlist'];
      $logFields['binaryannotationlist'] = $binaryannotationlist;
      $logFields['requesttype'] = $type;
      $logFields['logtype'] = $obj->logtype;
      $logFields['version'] = $obj->version;
      $logFields['level']   = $obj->level;
      $logFields['rpcid']   = $rpcData['rpcid'];

      if($noticeType){
        $obj->writeLog(json_encode($logFields, JSON_UNESCAPED_SLASHES), true); 
      }else{
        $obj->writeLog(json_encode($logFields, JSON_UNESCAPED_SLASHES)); 
      }
    }
    
    private function writeLog($strLogFields, $bolFlash=false) {
      $obj = self::getInstance();

      $logStr = sprintf($obj->statFormat, date("Y-m-d H:i:s"), $obj->logtype, $obj->pid, $strLogFields);

      $obj->buf = $obj->buf . $logStr;
      if($bolFlash === false && strlen($obj->buf) < 10000){
        return;
      }
      if(!@is_dir($obj->logDir)){
        @mkdir($obj->logDir);
      }
      $ret = @file_put_contents($obj->logFile, $obj->buf, FILE_APPEND);
      if($ret){
        $obj->buf = "";
      }
      return;
    }
}
