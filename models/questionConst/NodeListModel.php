<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/1
 * Time: 下午6:25
 */

namespace app\models\questionConst;


use app\models\beans\NodeListBean;
use app\models\question\CommonModel;

class NodeListModel
{
    const TABLE_NAME = "node_list";
    private static $db;

    public static function getDb(){
        if(self::$db == null){
            self::$db = \Yii::$app->db_quest_const;
        }
        return self::$db;
    }

    public static function convertDbToBeans($aData){
        if(!is_array($aData) || empty($aData)) {
            return [];
        }
        return array_map(function($d){return new NodeListBean($d);}, $aData);
    }

    public static function convertDbToBean($aData){
        return new NodeListBean($aData);
    }

    public static function insertOneRecord(NodeListBean $nodeListBean){
        $aInsertData = $nodeListBean->toArray();
        $aInsertData = array_filter($aInsertData, function($item){return !is_null($item);});
        try{
            $rowNum = self::getDb()->createCommand()->insert(self::TABLE_NAME, $aInsertData)->execute();
        }catch(\Exception $e){
            throw new \Exception("insert db error, data is:" . json_encode($aInsertData));
        }
        return $rowNum;
    }

    /**
     * @param $grade
     * @param $subject
     * @param $version
     * @param $module
     * @return NodeListBean[]
     * @throws \Exception
     */
    public static function queryNodeListByCondition($grade, $subject, $version, $module){
        $aWhere = [
            'AND',
            ['=', 'grade', $grade],
            ['=', 'subject', $subject],
            ['=', 'version', $version],
            ['=', 'module', $module],
        ];
        try{
            $aData = CommonModel::createSelectCommand(self::getDb(), $aWhere, self::TABLE_NAME)->queryAll();
        }catch(\Exception $e){
            throw new \Exception('select db error,condition is:' . json_encode($aWhere));
        }
        return self::convertDbToBeans($aData);
    }
}