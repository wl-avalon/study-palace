<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/31
 * Time: 下午7:12
 */
namespace app\models\question;

use app\constants\QuestionDetailBeanConst;
use app\models\beans\QuestionDetailBean;
use yii\db\Query;

class QuestionDetailModel
{
    const TABLE_NAME = "question_detail";

    public static function convertDbToBeans($aData){
        if(!is_array($aData) || empty($aData)) {
            return [];
        }
        return array_map(function($d){return new QuestionDetailBean($d);}, $aData);
    }

    public static function convertDbToBean($aData){
        return new QuestionDetailBean($aData);
    }

    public static function insertOneRecord(QuestionDetailBean $questionDetailBean, $dbName){
        $aInsertData = $questionDetailBean->toArray();
        $aInsertData = array_filter($aInsertData, function($item){return !is_null($item);});
        try{
            $rowNum = CommonModel::getQuestionDb($dbName)->createCommand()->insert(self::TABLE_NAME, $aInsertData)->execute();
        }catch(\Exception $e){
            throw new \Exception("insert db error, message is:" . $e->getMessage());
        }
        return $rowNum;
    }

    /**
     * @param $minID
     * @param $name
     * @return QuestionDetailBean[]
     * @throws \Exception
     */
    public static function queryContainMathMlDetailList($minID, $name){
        $where = [
            'AND',
            ['>', 'id', $minID],
            [
                'OR',
                ['like', 'question_content', '%math xmlns%', false],
                ['like', 'question_answer', '%math xmlns%', false],
                ['like', 'question_analysis', '%math xmlns%', false],
            ],
            ['=', 'del_status', QuestionDetailBeanConst::DEL_STATUS_NORMAL]
        ];
        try{
            $aData = (new Query())->select([])->from(self::TABLE_NAME)->where($where)->limit(20)->orderBy('id')->createCommand(CommonModel::getQuestionDb($name))->queryAll();
        }catch(\Exception $e){
            throw new \Exception('select db error,condition is:' . json_encode($where));
        }
        return self::convertDbToBeans($aData);
    }

    public static function updateDetailContentAndAnswerAndAnalysis(QuestionDetailBean $questionDetailBean, $name){
        $where = [
            'id' => $questionDetailBean->getID(),
        ];
        $update = [
            'question_content'  => $questionDetailBean->getQuestionContent(),
            'question_answer'   => $questionDetailBean->getQuestionAnswer(),
            'question_analysis' =>$questionDetailBean->getQuestionAnalysis(),
        ];
        try{
            $updateRowNum = CommonModel::getQuestionDb($name)->createCommand()->update(self::TABLE_NAME, $update, $where)->execute();
        }catch(\Exception $e){
            throw new \Exception('update db error,condition is:' . json_encode($where));
        }
        return $updateRowNum;
    }
}