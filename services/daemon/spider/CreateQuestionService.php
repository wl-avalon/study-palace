<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/4
 * Time: 下午10:10
 */

namespace app\services\daemon\spider;


use app\apis\IDAllocApi;
use app\components\Format;
use app\components\SPLog;
use app\constants\QuestionRecordBeanConst;
use app\library\Request;
use app\models\beans\QuestionRecordBean;
use app\models\question\QuestionRecordModel;

class CreateQuestionService
{
    public static $baseQuestionUrl = "http://www.91taoke.com/Juanzi/ajaxlist";

    public static function foreachQuestionList($allEnum, $subjectName, $processIndex){
        foreach($allEnum as $gradeKey => $xueKeData){
            $xueKeList      = $xueKeData['data'];
            foreach($xueKeList as $subjectKey => $banBenData){
                $subjectChinese = $banBenData['value'];
                if($subjectChinese != $subjectName){
                    continue;
                }
                $banBenList     = $banBenData['data'];
                foreach($banBenList as $versionKey => $moduleData){
                    $mokuaiList = $moduleData['data'];
                    foreach($mokuaiList as $moduleKey => $module){
                        $nodeList           = $module['data']['nodeList'];
                        $difficultyMap      = $module['data']['nanDuMap'];
                        $questionTypeMap    = $module['data']['tiXingMap'];
                        foreach($nodeList as $nodeItem){
                            $nodeID = $nodeItem['id'];
                            foreach($difficultyMap as $difficultyKey => $difficulty){
                                foreach($questionTypeMap as $questionTypeKey => $questionType){
                                    self::createQuestionRecordList($gradeKey, $subjectKey, $versionKey, $moduleKey, $nodeID, $difficultyKey, $questionTypeKey, $subjectChinese, $processIndex);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private static function createQuestionRecordList($gradeKey, $subjectKey, $versionKey, $moduleKey, $nodeID, $difficulty, $questionType, $subjectChinese, $processIndex){
        $p = 1;
        $count = 0;
        do{
            $url = self::$baseQuestionUrl . "?id={$gradeKey},{$subjectKey},{$versionKey},{$moduleKey}&zjid={$nodeID}&tixing={$questionType}&nandu={$difficulty}&leixing=0&xuekename={$subjectChinese}&p={$p}";$p++;
            $questionList = self::getQuestion($url, $processIndex);
            if(empty($questionList)){
                break;
            }
            $count += count($questionList);
            foreach($questionList as $question){
                $condition          = ['grade' => $gradeKey, 'subject' => $subjectKey, 'version' => $versionKey, 'module' => $moduleKey, 'node' => $nodeID, 'difficulty' => $difficulty, 'questionType' => $questionType];
                $questionMd5        = Format::getQuestionMd5($question);
                $doneQuestionList   = QuestionRecordModel::queryQuestionListByMD5($questionMd5, $subjectChinese);
                if(!empty($doneQuestionList)){
                    $retry = self::checkRetryUpdateQuestion($doneQuestionList, $question, $condition, $subjectChinese);
                    if($retry){
                        continue;
                    }
                }
                do{
                    $createResult = self::createNewQuestion($question, $condition, $subjectChinese);
                }while(!$createResult);
            }
            SPLog::log("{$url}");
            usleep(100000);
        }while(true);
    }

    private static function getQuestion($url, $processIndex){
        $response   = Request::proxyCurl($url, $processIndex);
        $questList  = Format::getQuestInfo($response);
        $questionListRes = [];
        foreach($questList as $questInfo){
            $question   = Format::formatQuestionContent($questInfo['questionContent']);
            $result     = Format::formatResultContent($questInfo['resultContent']);

            $questionRemark = $question['questionRemark'];
            $questionList   = $question['questionList'];
            $resultRemark   = $result['resultRemark'];
            $resultList     = $result['resultList'];

            $list = [];
            foreach($questionList as $key => $questionText){
                $item = [
                    'question'  => $questionText,
                    'result'    => $resultList[$key] ?? "",
                ];
                $list[] = $item;
            }
            $questionListRes[] = [
                'questionRemark'    => $questionRemark,
                'resultRemark'      => $resultRemark,
                'questList'         => $list,
            ];
        }
        return $questionListRes;
    }

    /**
     * 校验是否重入,如果重入了,就更新数据
     * @param QuestionRecordBean[] $doneQuestionList
     * @param $question
     * @param $condition
     * @param $subjectChinese
     * @return bool
     */
    private static function checkRetryUpdateQuestion($doneQuestionList, $question, $condition, $subjectChinese){
        $seemQuestion = new QuestionRecordBean([]);
        foreach($doneQuestionList as $doneQuestionBean){
            $doneQuestion = json_decode($doneQuestionBean->getWorkContent(), true);
            if($question['questionRemark'] != $doneQuestion['questionRemark'] || $question['resultRemark'] != $doneQuestion['resultRemark']){
                return false;
            }
            $index = 0;
            foreach($doneQuestion['questList'] as $doneQuest){
                $quest = $question['questList'][$index++] ?? [];
                if(empty($quest) || $doneQuest['question'] != $quest['question'] || $doneQuest['result'] != $quest['result']){
                    return false;
                }
            }
            $seemQuestion = $doneQuestionBean;
            break;
        }
        $question['condition'] = $condition;
        $seemQuestion->setWorkContent(json_encode($question));
        QuestionRecordModel::updateQuestionWorkContent($seemQuestion, $subjectChinese);
        return true;
    }

    private static function createNewQuestion($question, $condition, $subjectChinese){
        $response = IDAllocApi::nextId();
        if(empty($response['data']['nextId'])){
            return false;
        }
        $uuid = $response['data']['nextId'];
        $question['condition'] = $condition;
        $questionBeanData = [
            'uuid'                  => $uuid,
            'question_md5'          => Format::getQuestionMd5($question),
            'question_creator_id'   => '',
            'question_remark'       => $question['questionRemark'],
            'work_status'           => QuestionRecordBeanConst::RECORD_STATUS_WAIT_PROCESS,
            'work_content'          => json_encode($question),
            'create_time'           => date('Y-m-d H:i:s'),
        ];
        $questionBean = new QuestionRecordBean($questionBeanData);
        QuestionRecordModel::insertOneRecord($questionBean, $subjectChinese);
        unset($questionBean);
        return true;
    }
}