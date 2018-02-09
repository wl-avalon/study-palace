<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/1
 * Time: 上午11:28
 */

namespace app\models\question;
use app\constants\QuestionRecordBeanConst;
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
     * 根据MD5查询内容相同的问题
     * @param $md5
     * @param $name
     * @return QuestionRecordBean[]
     * @throws \Exception
     */
    public static function queryQuestionListByMD5($md5, $name){
        $aWhere = [
            'question_md5' => $md5,
        ];
        try{
            $aData = CommonModel::createSelectCommand(CommonModel::getQuestionDb($name), $aWhere, self::TABLE_NAME)->queryAll();
        }catch(\Exception $e){
            throw new \Exception('select db error,condition is:' . json_encode($aWhere));
        }
        return self::convertDbToBeans($aData);
    }

    /**
     * 更新记录内容
     * @param QuestionRecordBean $question
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public static function updateQuestionWorkContent(QuestionRecordBean $question, $name){
        $aWhere = [
            'id'    => $question->getID(),
        ];
        $aUpdate = [
            'work_content'  => $question->getWorkContent(),
        ];
        try{
            $updateRowNum = CommonModel::getQuestionDb($name)->createCommand()->update(self::TABLE_NAME, $aUpdate, $aWhere)->execute();
        }catch(\Exception $e){
            throw new \Exception('update db error,condition is:' . json_encode($aWhere));
        }
        return $updateRowNum;
    }

    /**
     * 查询仍未拆包的问题记录
     * @param $minID
     * @param $name
     * @return QuestionRecordBean[]
     * @throws \Exception
     */
    public static function queryWaitWorkToQuestionDetailRecordList($minID, $name){
        $aWhere = [
            'AND',
            ['=', 'work_status', QuestionRecordBeanConst::RECORD_STATUS_WAIT_PROCESS],
            ['>', 'id', $minID],
        ];
        try{
            $aData = CommonModel::createSelectCommand(CommonModel::getQuestionDb($name), $aWhere, self::TABLE_NAME)->queryAll();
        }catch(\Exception $e){
            throw new \Exception('select db error,condition is:' . json_encode($aWhere));
        }
        return self::convertDbToBeans($aData);
    }

    /**
     * 将记录置为处理中
     * @param QuestionRecordBean $question
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public static function updateWorkStatusToProcessing(QuestionRecordBean $question, $name){
        $aWhere = [
            'id'            => $question->getID(),
            'work_status'   => QuestionRecordBeanConst::RECORD_STATUS_WAIT_PROCESS,
        ];
        $aUpdate = [
            'work_status'   => QuestionRecordBeanConst::RECORD_STATUS_PROCESSING,
        ];
        try{
            $updateRowNum = CommonModel::getQuestionDb($name)->createCommand()->update(self::TABLE_NAME, $aUpdate, $aWhere)->execute();
        }catch(\Exception $e){
            throw new \Exception('update db error,condition is:' . json_encode($aWhere));
        }
        return $updateRowNum;
    }

    /**
     * 将记录置为处理完
     * @param QuestionRecordBean $question
     * @param $name
     * @return mixed
     * @throws \Exception
     */
    public static function updateWorkStatusToDone(QuestionRecordBean $question, $name){
        $aWhere = [
            'id'            => $question->getID(),
            'work_status'   => QuestionRecordBeanConst::RECORD_STATUS_PROCESSING,
        ];
        $aUpdate = [
            'work_status'   => QuestionRecordBeanConst::RECORD_STATUS_PROCESS_DONE,
        ];
        try{
            $updateRowNum = CommonModel::getQuestionDb($name)->createCommand()->update(self::TABLE_NAME, $aUpdate, $aWhere)->execute();
        }catch(\Exception $e){
            throw new \Exception('update db error,condition is:' . json_encode($aWhere));
        }
        return $updateRowNum;
    }
}