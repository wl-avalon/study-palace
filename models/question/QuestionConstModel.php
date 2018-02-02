<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/1
 * Time: 上午11:48
 */

namespace app\models\question;


use app\models\beans\QuestionConstBean;

class QuestionConstModel
{
    const TABLE_NAME = "question_record";
    private static $db = null;

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
        return array_map(function($d){return new QuestionConstBean($d);}, $aData);
    }

    public static function convertDbToBean($aData){
        return new QuestionConstBean($aData);
    }

    public static function insertOneRecord(QuestionConstBean $QuestionRecordBean){
        $aInsertData = $QuestionRecordBean->toArray();
        $aInsertData = array_filter($aInsertData, function($item){return !is_null($item);});
        try{
            $rowNum = self::getDb()->createCommand()->insert(self::TABLE_NAME, $aInsertData)->execute();
        }catch(\Exception $e){
            throw new \Exception("insert db error, data is:" . json_encode($aInsertData));
        }
        return $rowNum;
    }

    /**
     * @param $tagKey
     * @return QuestionConstBean
     * @throws \Exception
     */
    public static function queryQuestionListByTagKey($tagKey){
        $aWhere = [
            'AND',
            ['=', 'tag_key', $tagKey],
        ];
        try{
            $aData = CommonModel::createSelectCommand(self::getDb(), $aWhere, self::TABLE_NAME)->queryOne();
        }catch(\Exception $e){
            throw new \Exception('select db error,condition is:' . json_encode($aWhere));
        }
        return self::convertDbToBean($aData);
    }
}