<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/1
 * Time: 上午11:28
 */

namespace app\models\question;
use app\models\beans\QuestionRecordBean;

class QuestionRecordModel
{
    const TABLE_NAME = "question_record";

    public static function convertDbToBeans($aData){
        if(!is_array($aData) || empty($aData)) {
            return [];
        }
        return array_map(function($d){return new QuestionRecordBean($d);}, $aData);
    }

    public static function convertDbToBean($aData){
        return new QuestionRecordBean($aData);
    }

    public static function insertOneRecord(QuestionRecordBean $QuestionRecordBean, $dbName){
        $aInsertData = $QuestionRecordBean->toArray();
        $aInsertData = array_filter($aInsertData, function($item){return !is_null($item);});
        try{
            $rowNum = CommonModel::getQuestionDb($dbName)->createCommand()->insert(self::TABLE_NAME, $aInsertData)->execute();
        }catch(\Exception $e){
            throw new \Exception("insert db error, data is:" . json_encode($aInsertData));
        }
        return $rowNum;
    }

    /**
     * @param $md5
     * @param $name
     * @return QuestionRecordBean[]
     * @throws \Exception
     */
    public static function queryQuestionListByMD5($md5, $name){
        $aWhere = [
            'AND',
            ['=', 'question_md5', $md5],
        ];
        try{
            $aData = CommonModel::createSelectCommand(CommonModel::getQuestionDb($name), $aWhere, self::TABLE_NAME)->queryAll();
        }catch(\Exception $e){
            throw new \Exception('select db error,condition is:' . json_encode($aWhere));
        }
        return self::convertDbToBeans($aData);
    }
}