<?php
namespace rrxframework\util;
use Yii;

class RpcInfoUtil {
  protected $rpcId = "0";
  protected $rpcBol = ".";
  protected $rpcIter = 0;
  
  static protected $instance;

  static private function getInstance() {
    if (empty(self::$instance)) {
      self::$instance = new RpcInfoUtil();
      if (isset($_SERVER['HTTP_JDB_HEADER_RPC_ID'])){
          self::$instance->rpcId = trim($_SERVER['HTTP_JDB_HEADER_RPC_ID']) . ".1";
      }
    } 
    return self::$instance;
  }
  
  public static function getNextRpcId()
  {
    $obj = self::getInstance();
    $obj->rpcIter = $obj->rpcIter + 1;
    return $obj->rpcId . $obj->rpcBol . $obj->rpcIter;
  }

  public static function getCurrentRpcId()
  {
    $obj = self::getInstance();
    return $obj->rpcId . $obj->rpcBol . $obj->rpcIter;
  }

  public static function getServerRpcId()
  {
    $obj = self::getInstance();
    return $obj->rpcId;
  }
}
