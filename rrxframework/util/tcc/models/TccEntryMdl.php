<?php

namespace rrxframework\util\tcc\models;
use rrxframework\util\tcc\config\TccConsts;

use PDO;
use Yii;
use yii\db\Connection;
use yii\db\ActiveRecord;

/*
create table tcc_transcation(
  `id`  bigint(20) NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `transcation_id` char(32) NOT NULL COMMENT '流程号ID',
  `decision_type` tinyint(4) DEFAULT NULL COMMENT '流程类型,0=>内部决策,1=>外部决策',
  `content` varchar(8000) DEFAULT NULL COMMENT '流程中间数据存储',
  `descision_content` varchar(8000) DEFAULT NULL COMMENT '决策数据存储',
  `control_class_name` varchar(64) DEFAULT NULL COMMENT '控制器类名字,用于恢复现场',
  `process_result` tinyint(4) DEFAULT NULL COMMENT '单次流程的处理结果态, 0/1/2 失败/成功/工单',
  `process_status` tinyint(4) DEFAULT NULL COMMENT '流程状态标识别,0=>start,1=>process,2=>end',
  `create_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '流程启动时间',
  `update_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '流程修改时间',
  PRIMARY KEY (`id`),
  unique key `transcation_id` (`transcation_id`),
  KEY `process_status` (`process_status`, `create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

delete descision_content
*/

class TccEntryMdl extends ActiveRecord {
    public static $objMysql = null;
    public static $strTableName = 'tcc_transcation';

    public static function getdb(){
      if(self::$objMysql == null){
        self::$objMysql = Yii::$app->db_tcc;    
      }
      return self::$objMysql;
    }

    public static function getTablePrefix(){
      return self::$strTableName;
    }

    public static function createTccEntry($arrData){
      $intTime = time();
      $data['transcation_id']       = $arrData['transcation_id'];
      $data['decision_type']       = $arrData['decision_type'];
      $data['content']              = $arrData['content'];
      //$data['descision_content']    = $arrData['descision_content'];
      $data['control_class_name']   = $arrData['control_class_name'];
      $data['process_status']       = $arrData['process_status'];  
      $data['create_time']          = date('Y-m-d H:i:s', $intTime);
      $data['update_time']          = date('Y-m-d H:i:s', $intTime);

      foreach ($data as $key => &$value) {
        # code...
        if(is_string($value)){
          $value = mysql_escape_string($value);
        }
      }
      unset($value);

      //ignore duplicate key error
      $sql = sprintf("insert ignore into %s (transcation_id, decision_type, content,
        control_class_name, process_status, create_time,
        update_time) values ('%s', %d, '%s', '%s', %d, '%s', '%s');",
        self::getTablePrefix(), $data['transcation_id'], $data['decision_type'], $data['content'], $data['control_class_name'], $data['process_status'], $data['create_time'], $data['update_time']);
      return self::getDb()->createCommand($sql)->execute();
    }

    public static function updateProcessEntry($arrData){
        $data['process_status'] = TccConsts::PROCESS_PROCESS;
        $data['content']        = $arrData['content'];
        $data['update_time']    = date('Y-m-d H:i:s', time());     
        return self::getDb()->createCommand()->update(self::getTablePrefix(), $data,
          array('transcation_id' => $arrData['transcation_id']))->execute();
    }

    public static function updateDecisionEntry($arrData){
        $data['process_result']     = $arrData['decision_result'];
        $data['update_time']    = date('Y-m-d H:i:s', time());     
        return self::getDb()->createCommand()->update(self::getTablePrefix(), $data,
          array('transcation_id' => $arrData['transcation_id']))->execute();
    }

    public static function finishProcessEntry($arrData){
        $data['process_status'] = TccConsts::PROCESS_END;
        $data['update_time']    = date('Y-m-d H:i:s', time());
        return self::getDb()->createCommand()->update(self::getTablePrefix(), $data,
          array('transcation_id' => $arrData['transcation_id']))->execute();        
    }

    public static function getUncompleteActivity($arrData){
        $tbl =  self::getTablePrefix();     
        $sql = "select * from `{$tbl}` where process_status != :process_status and update_time <= :update_time order by update_time limit :limit;";
        return parent::findBySql($sql, array(':process_status' => $arrData['process_status'], ':update_time' => $arrData['update_time'], ':limit' => $arrData['limit']))->asArray()->all();
    }

    public static function getActivityByEntryID($entryID){
        $tbl =  self::getTablePrefix();
        $sql = "select * from `{$tbl}` where transcation_id = :transcation_id;";
        return parent::findBySql($sql, array(':transcation_id' => $entryID))->asArray()->one();
    }
}

?>