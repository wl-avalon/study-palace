<?php
namespace rrxframework\util\tcc;
use rrxframework\util\tcc\config\TccConsts;

class TccBaseImpl{
  //requestId：请求唯一id，原则调用alloc服务
  public $requestId = "";

  //requestType, 默认按外部决策处理
  public $requestType = 1;

  //锁定中间结果保存，用于后续提交订单、成功通知等相关数据的保留
  public $processData = array();

  //决策中间结果保留
  public $decisionData = array();

  //返回结果，用于输出
  public $resultData = array();

  //本次请求是否
  /*
    1 => 成功
    2 => 失败
    3 => 工单
  */
  public $requestStatus = TccConsts::PAY_LOCK;

  public function __construct($requestId, $requestType){
    $this->requestId = $requestId;
    $this->requestType = $requestType;
  }

  /*
    tcc资源锁定接口
    output:
      false: 锁定失败
      true：锁定资源data
  */
  public function requestResourceLock($arrInput){
    return false;
  }

  /*
    决策系统
      1）外部决策：调用外部方法
      2）内部决策：requestResourceLock为成功的状态，requestDecision原则上是返回成功；

    需要设置:$requestStatus

    input:
        $this->lockProcessData
    output:
        false/true
  */
  public function requestDecision($arrProcessData){
    return false;
  }

   /*
    提交或者回滚操作，是否完备
    output:
      false/true 
  */
  public function requestExecute($requestStatus, $arrProcessData, $arrDecisionData){
    return false;
  }

  /*
    通过requestId查询本次请求是否成功
      1）外部决策：调用外部方法，常见查询订单系统;
      2）内部决策：requestStatus=2直接回滚处理;
    
    需要设置:$requestStatus

    input:
        requestId
    output:
        false/true
  */
  public function requestDecisionCheck($requestId){
    return false;
  }

  public function getRequestId(){
    return $this->requestId;
  }

  public function setRequestId($value){
    $this->requestId = $value;
    return true;
  }

  public function getProcessData(){
    return $this->processData;
  }

  public function setProcessData($value){
    $this->processData = $value;
    return true;
  }

  public function getRequestType(){
    return $this->requestType;
  }

  public function setRequestType($value){
    $this->requestType = $value;
    return true;
  }

  public function getDecisionData(){
    return $this->decisionData;
  }

  public function getRequestStatus(){
    return $this->requestStatus;
  }

  public function setRequestStatus($value){
    $this->requestStatus = $value;
    return true;
  }
}

?>
