<?php
namespace rrxframework\util\tcc;
use rrxframework\base\JdbLog;
use rrxframework\util\ServiceWfUtil;
use rrxframework\util\DictUtil;
use rrxframework\util\tcc\TccBaseImpl;
use rrxframework\util\tcc\service\TccService;
use rrxframework\util\tcc\api\TccController;
use rrxframework\util\tcc\config\TccConsts;

class TccConsole{
  public static function execute(){
    //get uncomplete task
    $startTime = time();
    $executeTime = $startTime;
    while($executeTime - $startTime < TccConsts::MAX_EXECUTE_TIME){
      if(self::logicExecute() == false){
        sleep(TccConsts::MAX_IDLE_TIME);
      }
      $executeTime = time();
    }

  }

  public static function logicExecute(){
    $timeNow = time();
    $arrInput['updateTime'] = date("Y-m-d H:i:s" , $timeNow - TccConsts::MAX_WAIT_TIME);
    $arrInput['limit'] = TccConsts::LIMIT_NUM;
    $ret = TccService::getUncompleteActivity($arrInput);
    if(isset($ret['errno']) && $ret['errno'] != 0){
      JdbLog::warning("fail to call getUncompleteActivity error!");
      return false;
    }
    $arrUncompleteSet = $ret['data'];

    //$i = 0;
    if(!empty($arrUncompleteSet)){
      foreach ($arrUncompleteSet as $key => $value) {
        # code...
        $resultStatus = $value['process_result'];
        $resultTime   = strtotime($value['update_time']);
        if(in_array($resultStatus, TccConsts::$resultSet) && ($timeNow - $resultTime) <= TccConsts::MAX_LOCK_WAIT_TIME){
          JdbLog::debug("lock time too short, wait a while. input:".serialize($value));
          continue;
        }

        $ret = self::doUncomplete($value);
        if($ret){
          JdbLog::debug("succ execute : ". $value['transcation_id']);
        }else{
          JdbLog::debug("fail execute : ". $value['transcation_id']);
          self::monitorLock($value);
        }
      }      
    }else{
      sleep(TccConsts::MAX_IDLE_TIME);
    }
    JdbLog::notice("execute num:".count($arrUncompleteSet));
    return true;
  }

  public static function doUncomplete($arrData){
    $ssStartTime = DictUtil::microtimeFloat();
    $implName = $arrData['control_class_name'];
    $processData  = empty($arrData['content']) ? array() : json_decode($arrData['content'], true);
    $transcationId = $arrData['transcation_id'];
    $requestType = $arrData['type'];
    $requestStatus = $arrData['process_result'];

    //实现类状态恢复
    $objImpl = new $implName($transcationId, $requestType);
    $objImpl->setProcessData($processData);
    $objImpl->setRequestStatus($requestStatus);

    //var_dump($objImpl->getProcessData());
    //var_dump($objImpl->getRequestStatus());

    $descisionStatus = TccConsts::PAY_LOCK;
    //var_dump($arrData['transcation_id']);
    try{
      //requestDecisionCheck
      $decisionStatus = $objImpl->requestDecisionCheck($transcationId);
    }catch(\Exception $e){
      JdbLog::warning($implName ." requestDecisionCheck fail. msg:".$e->getMessage());
      return false;
    }
    if($decisionStatus == false){
      //$objImpl->setRequestStatus(TccConsts::PAY_LOCK);
      return false;
    }

    //记录决策结果
    $arrInput['transcationId'] = $transcationId;
    $arrInput['decisionStatus'] = $objImpl->getRequestStatus();
    self::updateDecisionTcc($arrInput);
   
    try{
      //requestExecute
      $decisionData = $objImpl->getDecisionData();
      $requestStatus = $objImpl->getRequestStatus();
      $executeStatus = $objImpl->requestExecute($requestStatus, $processData, $decisionData);
    }catch(\Exception $e){
      JdbLog::warning($implName ." requestExecute fail. msg:".$e->getMessage());
      return false;
    }      

    //finish tcc process
    if($executeStatus && in_array($objImpl->getRequestStatus(), TccConsts::$resultSet)){
      self::finishTcc($transcationId);
      $wfErrno = $objImpl->getRequestStatus() - 1;
      $ssEndTime = DictUtil::microtimeFloat();
      ServiceWfUtil::noticeLog($wfErrno, $ssStartTime, $ssEndTime, 0, '', $transcationId);
    }
    
    if(!in_array($objImpl->getRequestStatus(), TccConsts::$resultSet)){
        self::monitorLock($arrData);
    }
    return true;
  }

  public static function monitorLock($arrData){
      if(empty($arrData['transcation_id']) || empty($arrData['create_time'])){
          return;
      }

      $timeNow = time();
      if($timeNow - strtotime($arrData['create_time']) >= TccConsts::MONITOR_MAX_TIME_FOR_LOCK){
          JdbLog::warning("lock time too long, transcation_id=".$arrData['transcation_id']);
      }
  }
  
  public static function updateDecisionTcc($arrInput=array()){
    $arrData['transcation_id']  = $arrInput['transcationId'];
    $arrData['decisionResult'] = $arrInput['decisionStatus'];
    //$arrData['descisionContent'] = $arrInput['descisionContent'];
    
    $ret = TccService::updateDecisionEntry($arrData);
    if(isset($ret['errno']) && $ret['errno'] != 0){
      JdbLog::warning("fail to update tcc transcation error! input:".serialize($arrData));
      return false;
    }

    return true;
  }

  public static function finishTcc($transcationId){
    $arrData['transcation_id']  = $transcationId;
    $ret = TccService::finishProcessEntry($arrData);
    if(isset($ret['errno']) && $ret['errno'] != 0){
      JdbLog::warning("fail to finish tcc transcation error! input:".serialize($arrData));
      return false;
    }

    return true;
  }
}

?>