<?php

namespace rrxframework\util\tcc\models;
use rrxframework\util\tcc\config\TccConsts;

use PDO;
use Yii;
use yii\db\Connection;
use yii\db\ActiveRecord;
use rrxframework\util\tcc\TccConsole;

/*
CREATE TABLE `tcc_order` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `sharding` varchar(32) NOT NULL DEFAULT '' COMMENT '分表字段值',
  `order_id` varchar(32) NOT NULL DEFAULT '' COMMENT 'tcc订单id，唯一标示一次tcc操作',
  `step` tinyint(3) unsigned NOT NULL COMMENT 'tcc已完成的步骤，0=try，1=commit，2=cancel',
  `create_time` int(11) unsigned NOT NULL COMMENT '记录创建时间戳',
  `update_time` int(11) unsigned NOT NULL COMMENT '记录最后更新时间戳',
  PRIMARY KEY (`id`),
  unique key `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

class TccOrderMdl extends ActiveRecord {
    public static $objMysql = null;
    public static $strTableName = 'tcc_order';

    public static function getdb(){
      if(self::$objMysql == null){
        self::$objMysql = Yii::$app->db_tcc;    
      }
      return self::$objMysql;
    }

    public static function setdb($db){
        self::$objMysql = $db;
    }
    
    public static function tableName(){
      return self::$strTableName;
    }

    public static function getTccOrder($sharding, $orderID){
        $conds =  [
            'sharding' => $sharding,
            'order_id' => $orderID,            
        ];

        $ret = parent::find()->where($conds)->asArray()->one();
        return $ret;
    }
    
    public static function createTccOrder($sharding, $orderID){
      $intTime = time();
      $fields = [
          'sharding' => $sharding,
          'order_id' => $orderID,
          'step' => TccConsts::ORDER_STEP_TRY,
          'create_time' => $intTime,
          'update_time' => $intTime,
      ];

      return self::getDb()->createCommand()->insert(self::tableName(), $fields)->execute();
    }

    public static function updateTccOrderStep($sharding, $orderID, $step){
        $fields = [
            'step' => $step,
        ];
        $conds = [
            'sharding' => $sharding,
            'order_id' => $orderID,
            'step' => TccConsts::ORDER_STEP_TRY,
        ];    
        return self::getDb()->createCommand()->update(self::tableName(), $fields, $conds)->execute();
    }
    
}

?>