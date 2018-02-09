<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/9
 * Time: 下午4:52
 */

namespace app\services\daemon\process;


use app\apis\IDAllocApi;
use app\components\SPLog;
use app\models\beans\QuestionDetailBean;
use app\models\beans\QuestionRecordBean;
use app\models\question\QuestionDetailModel;
use app\models\question\QuestionRecordModel;

class CreateQuestionDetailService
{
    public static function execute($processName){
        $id = 0;
        while(true){
            $questionRecordBeanList = QuestionRecordModel::queryWaitWorkToQuestionDetailRecordList($id, $processName);
            if(empty($questionRecordBeanList)){
                break;
            }

            foreach($questionRecordBeanList as $questionRecordBean){
                $id = $questionRecordBean->getID();
                do{
                    $explodeResult = self::explodeQuestionRecord($questionRecordBean, $processName);
                    if(!$explodeResult){
                        SPLog::warning("解析失败,数据为:" . json_encode($questionRecordBean->toArray()));
                        sleep(1);
                    }
                }while(!$explodeResult);
            }
        }
    }

    private static function explodeQuestionRecord(QuestionRecordBean $questionRecordBean, $name){
        $updateRowNum = QuestionRecordModel::updateWorkStatusToProcessing($questionRecordBean, $name);
        if($updateRowNum <= 0){
            return true;
        }
        $jsonWorkContent    = $questionRecordBean->getWorkContent();
        $workContent        = json_decode($jsonWorkContent, true);
        $questionList       = $workContent['questList'];
        $condition          = $workContent['condition'];
        $idResponse         = IDAllocApi::batch(count($questionList));
        if(empty($idResponse['data'])){
            return false;
        }
        $uuidList           = explode(',', $idResponse['data']);
        $allDone            = true;
        $i = 0;
        foreach($questionList as $questionInfo){
            $questionDetailBeanData = [
                'uuid'                      => $uuidList[$i++],
                'question_record_id'        => $questionRecordBean->getUuid(),
                'question_content'          => $questionInfo['question'] ?? "",
                'question_answer'           => $questionInfo['result']['answer'] ?? "",
                'question_analysis'         => $questionInfo['result']['analysis'] ?? "",
                'question_knowledge_point'  => $questionInfo['result']['knowledge_point'] ?? "",
                'question_question_point'   => $questionInfo['result']['question_point'] ?? "",
                'grade'                     => $condition['grade'],
                'subject'                   => $condition['subject'],
                'version'                   => $condition['version'],
                'module'                    => $condition['module'],
                'question_type'             => $condition['questionType'],
                'create_time'               => date('Y-m-d H:i:s'),
            ];
            switch($questionInfo['result']['difficulty']){
                case "基础题":{
                    $questionDetailBeanData['difficulty'] = 1;
                    break;
                }
                case "中档题":{
                    $questionDetailBeanData['difficulty'] = 2;
                    break;
                }
                case "较难题":{
                    $questionDetailBeanData['difficulty'] = 3;
                    break;
                }
                default:{
                    $questionDetailBeanData['difficulty'] = $questionInfo['result']['difficulty'];
                }
            }
            $questionDetailBean = new QuestionDetailBean($questionDetailBeanData);
            $insertResult       = QuestionDetailModel::insertOneRecord($questionDetailBean, $name);
            if($insertResult <= 0){
                $allDone = false;
            }
        }
        if($allDone){
            QuestionRecordModel::updateWorkStatusToDone($questionRecordBean, $name);
        }
        return true;
    }
}