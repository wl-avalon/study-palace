<?php

namespace rrxframework\util\tcc\service;
use rrxframework\base\JdbLog;
use rrxframework\util\tcc\models\TccEntryMdl;
use rrxframework\util\tcc\config\TccConsts;

class TccService {
    public static $arrOutput = array(
      'errno'   => 0,
      'errmsg'  => 'ok',
      'data'    => array(),
    );

    public static function createTccEntry($arrInput){
      $data['transcation_id']       = $arrInput['transcation_id'];
      $data['decision_type']       = $arrInput['decision_type'];
      $data['content']              = isset($arrInput['content']) ? $arrInput['content'] : '';
      //$data['descision_content']    = isset($arrInput['descision_content']) ? $arrInput['descision_content'] : '';
      $data['control_class_name']   = $arrInput['control_class_name'];
      $data['process_status']       = isset($arrInput['process_status']) ? $arrInput['process_status'] : 0;
      $data['process_result']       = isset($arrInput['process_result']) ? $arrInput['[process_result'] : 0;
      try{
        $ret = TccEntryMdl::createTccEntry($data);
      }catch(\Exception $e){
        JdbLog::warning(__FUNCTION__." error. PDO exception: errcode: ".$e->getCode().", errmsg:".$e->getMessage());
        self::$arrOutput['errno'] = 2;
        self::$arrOutput['errmsg'] = 'fail to call pdo';
        return self::$arrOutput;  
      }
      self::$arrOutput['data'] = $ret; 
      return self::$arrOutput;   
    }

    public static function updateProcessEntry($arrInput){
      $data['content']        = $arrInput['content'];
      $data['transcation_id'] = $arrInput['transcation_id'];
      try{
        $ret = TccEntryMdl::updateProcessEntry($data);
      }catch(\Exception $e){
        JdbLog::warning(__FUNCTION__." error. PDO exception: errcode: ".$e->getCode().", errmsg:".$e->getMessage());
        self::$arrOutput['errno'] = 2;
        self::$arrOutput['errmsg'] = 'fail to call pdo';
        return self::$arrOutput;  
      }
      self::$arrOutput['data'] = $ret; 
      return self::$arrOutput;  
    }

    public static function updateDecisionEntry($arrInput){
      $data['decision_result']  = $arrInput['decisionResult'];
      //$data['decision_content'] = $arrInput['descisionContent'];
      $data['transcation_id'] = $arrInput['transcation_id'];
      try{
        $ret = TccEntryMdl::updateDecisionEntry($data);
      }catch(\Exception $e){
        JdbLog::warning(__FUNCTION__." error. PDO exception: errcode: ".$e->getCode().", errmsg:".$e->getMessage());
        self::$arrOutput['errno'] = 2;
        self::$arrOutput['errmsg'] = 'fail to call pdo';
        return self::$arrOutput;  
      }
      self::$arrOutput['data'] = $ret; 
      return self::$arrOutput;  
    }

    public static function finishProcessEntry($arrInput){
      $data['transcation_id'] = $arrInput['transcation_id'];
      try{
        $ret = TccEntryMdl::finishProcessEntry($data);
      }catch(\Exception $e){
        JdbLog::warning(__FUNCTION__." error. PDO exception: errcode: ".$e->getCode().", errmsg:".$e->getMessage());
        self::$arrOutput['errno'] = 2;
        self::$arrOutput['errmsg'] = 'fail to call pdo';
        return self::$arrOutput;  
      }
      self::$arrOutput['data'] = $ret; 
      return self::$arrOutput;        
    }

    public static function getUncompleteActivity($arrInput=array()){
      $data['process_status'] = TccConsts::PROCESS_END;
      $data['update_time'] = $arrInput['updateTime'];
      $data['limit']   = $arrInput['limit'];
      try{
        $ret = TccEntryMdl::getUncompleteActivity($data);
      }catch(\Exception $e){
        JdbLog::warning(__FUNCTION__." error. PDO exception: errcode: ".$e->getCode().", errmsg:".$e->getMessage());
        self::$arrOutput['errno'] = 2;
        self::$arrOutput['errmsg'] = 'fail to call pdo';
        return self::$arrOutput;  
      }
      self::$arrOutput['data'] = $ret;
      return self::$arrOutput;      
    }
    
    public static function getActivityByEntryID($entryID){
        try{
            $ret = TccEntryMdl::getActivityByEntryID($entryID);
        }catch(\Exception $e){
            JdbLog::warning(__FUNCTION__." error. PDO exception: errcode: ".$e->getCode().", errmsg:".$e->getMessage());
            self::$arrOutput['errno'] = 2;
            self::$arrOutput['errmsg'] = 'fail to call pdo';
            return self::$arrOutput;
        }
        self::$arrOutput['data'] = $ret;
        return self::$arrOutput;
    }
}

?>