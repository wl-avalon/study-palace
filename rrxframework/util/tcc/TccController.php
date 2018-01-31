<?php
namespace rrxframework\util\tcc;
use rrxframework\base\JdbLog;
use rrxframework\util\tcc\TccBaseImpl;
use rrxframework\util\tcc\service\TccService;
use rrxframework\util\tcc\api\idAllocApi;
use rrxframework\util\tcc\config\TccConsts;

/*
  succ : 0=>undefined, 1=>succ，2=>fail
  complete : 0=>uncomplete, 1=>complete
*/

class TccController{
  public static $typeSet = array(0,1);

  public $obj = null;
  public $type = 0;
  public $classImplName = "";

  //public $complete = 0;
  public $lockStatus = false;   // false => fail , true => succ
  public $descisionStatus = false;  // false => fail , true => succ
  public $executeStatus = false; // false => fail , true => succ
  public $transcationId = "";

  public function __construct($objImpl){
    if(!$objImpl instanceof TccBaseImpl){
      JdbLog::warning("objImpl not instanceof TccBaseImpl");
      return null;
    }
    $this->type = $objImpl->getRequestType();

    if(!in_array($this->type, self::$typeSet)){
      JdbLog::warning("input type error, not in (1,2)");
      return null;
    }

    $this->obj  = $objImpl;
    $this->classImplName = get_class($objImpl);
    $this->transcationId = $objImpl->getRequestId();
  }

  /*
    input :
        用于requestResourceLock函数调用
    output:
        false/true : 本次请求执行完备
  */

  public function execute($arrInput){
    //启动活动管理器
    $ret = $this->startRecordTcc();
    if($ret === false){
      return false;
    }

    try{
      //调用资源锁定方法
      $this->lockStatus = $this->obj->requestResourceLock($arrInput);
    }catch(\Exception $e){
      JdbLog::warning($this->classImplName ." requestResourceLock fail. meg:".$e->getMessage());
      //锁定资源抛异常，当失败处理
      $this->lockStatus = false;
    }
    
    //记录业务中间处理结果
    $ret = $this->updateProcessTcc();
    if($ret === false){
      return false;
    }
    
    if ($this->lockStatus) {
      # code...
      try{
        //调用请求决策系统
        $processData = $this->obj->getProcessData();
        $this->decisionStatus = $this->obj->requestDecision($processData);
      }catch(\Exception $e){
          JdbLog::warning($this->classImplName ." requestDecision fail. meg:".$e->getMessage());
          return false;
      }
      if($this->decisionStatus == false){
        $this->obj->setRequestStatus(TccConsts::PAY_LOCK);      
      }        
    }else{
      //try error
      $this->obj->setRequestStatus(TccConsts::PAY_FAIL);
    }



    //记录决策结果[0/1/2  成功/失败/工单]
    $ret = $this->updateDecisionTcc();
    if($ret === false){
      return false;
    }    

    try{
      //requestExecute
      $processData = $this->obj->getProcessData();
      $decisionData = $this->obj->getDecisionData();
      $requestStatus = $this->obj->getRequestStatus();
      $this->executeStatus = $this->obj->requestExecute($requestStatus, $processData, $decisionData);
    }catch(\Exception $e){
      JdbLog::warning($this->classImplName ." requestExecute fail. meg:".$e->getMessage());
      return false;
    }

    //工单不执行完备操作，等待脚本callback
    //var_dump($this->obj->getRequestStatus());
    if($this->executeStatus && in_array($this->obj->getRequestStatus(), TccConsts::$resultSet)){
      //finish tcc process
      $this->finishTcc();
    }else{
      JdbLog::debug("request unfinish. transcationId:".$this->transcationId);
    }
    return true;
  }

  /*
   input :
   用于对工单状态的流水单重新决策
   output:
   false/true : 本次请求执行完备
   */
  
  public function reDecision(array $arrInput = []){
      $transcationInfo = $this->getActivityByEntryID($this->transcationId);
      if(empty($transcationInfo)){
          return false;
      }
      //process_status保证lock过
      //process_result保证曾经决策过，并结果为工单
      if($transcationInfo['process_status'] != TccConsts::PROCESS_PROCESS
          || $transcationInfo['process_result'] != TccConsts::PAY_LOCK){
          return false;
      }
      $this->lockStatus = true;
      $content = json_decode($transcationInfo['content'], true);
      if($content === false){
          return false;
      }
      
      $content = array_replace_recursive($content, $arrInput);
      $this->obj->setProcessData($content);
  
      # code...
      try{
          //调用请求决策系统
          $processData = $this->obj->getProcessData();
          $this->decisionStatus = $this->obj->requestDecision($processData);
      }catch(\Exception $e){
          JdbLog::warning($this->classImplName ." requestDecision fail. meg:".$e->getMessage());
          return false;
      }
      if($this->decisionStatus == false){
          $this->obj->setRequestStatus(TccConsts::PAY_LOCK);
      }

      //记录决策结果[0/1/2  成功/失败/工单]
      $ret = $this->updateDecisionTcc();
      if($ret === false){
          return false;
      }
  
      try{
          //requestExecute
          $processData = $this->obj->getProcessData();
          $decisionData = $this->obj->getDecisionData();
          $requestStatus = $this->obj->getRequestStatus();
          $this->executeStatus = $this->obj->requestExecute($requestStatus, $processData, $decisionData);
      }catch(\Exception $e){
          JdbLog::warning($this->classImplName ." requestExecute fail. meg:".$e->getMessage());
          return false;
      }
  
      //工单不执行完备操作，等待脚本callback
      //var_dump($this->obj->getRequestStatus());
      if($this->executeStatus && in_array($this->obj->getRequestStatus(), TccConsts::$resultSet)){
          //finish tcc process
          $this->finishTcc();
      }else{
          JdbLog::debug("request unfinish. transcationId:".$this->transcationId);
      }
      return true;
  }
  
  private function getTranscationId(){
    $ret = idAllocApi::getAllocId();
    if(isset($ret['errno']) && $ret['errno'] != 0){
      JdbLog::warning("fail to alloc id");
      return false;
    }

    $this->transcationId = $ret['data'];
    return true;
  }

  private function startRecordTcc($arrInput=array()){
    $arrData['decision_type']      = $this->type;
    $arrData['transcation_id']      = $this->transcationId;
    $arrData['control_class_name']  = $this->classImplName;

    $ret = TccService::createTccEntry($arrData);
    if(isset($ret['errno']) && $ret['errno'] != 0){
      JdbLog::warning("fail to tcc transcation error! input:".serialize($arrData));
      return false;
    }

    return true;
  }

  private function updateProcessTcc($arrInput=array()){
    $arrData['transcation_id']  = $this->transcationId;
    $arrData['content'] = json_encode($this->obj->getProcessData());

    if(strlen($arrData['content']) >= TccConsts::MAX_CONTENT_LENGTH){
      JdbLog::warning("processData too long. input:".strlen($arrData['content']));
      return false;
    }
    
    $ret = TccService::updateProcessEntry($arrData);
    if(isset($ret['errno']) && $ret['errno'] != 0){
      JdbLog::warning("fail to update tcc transcation error! input:".serialize($arrData));
      return false;
    }

    return true;
  }

  private function getActivityByEntryID($entryID){
      $ret = TccService::getActivityByEntryID($entryID);
      if(isset($ret['errno']) && $ret['errno'] != 0){
          JdbLog::warning("fail to get tcc transcation error! transcation_id=".$entryID);
          return [];
      }
  
      return $ret['data'];
  }
  
  private function updateDecisionTcc($arrInput=array()){
    $arrData['transcation_id']  = $this->transcationId;
    $arrData['decisionResult'] = $this->obj->getRequestStatus();
    //$arrData['decisionContent'] = json_encode($this->obj->getDecisionRes());
    
    $ret = TccService::updateDecisionEntry($arrData);
    if(isset($ret['errno']) && $ret['errno'] != 0){
      JdbLog::warning("fail to update tcc transcation error! input:".serialize($arrData));
      return false;
    }

    return true;
  }

  private function finishTcc($arrInput=array()){
    $arrData['transcation_id']  = $this->transcationId;
    $ret = TccService::finishProcessEntry($arrData);
    if(isset($ret['errno']) && $ret['errno'] != 0){
      JdbLog::warning("fail to finish tcc transcation error! input:".serialize($arrData));
      return false;
    }

    return true;
  }

}

?>