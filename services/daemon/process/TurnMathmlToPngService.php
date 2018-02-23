<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/23
 * Time: 上午10:24
 */

namespace app\services\daemon\process;


use app\components\Format;
use app\models\beans\QuestionDetailBean;
use app\models\question\QuestionDetailModel;

class TurnMathmlToPngService
{
    public static function execute($processName, $id = 0){
        while(true){
            $questionRecordBeanList = QuestionDetailModel::queryContainMathMlDetailList($id, $processName);
            if(empty($questionRecordBeanList)){
                break;
            }

            foreach($questionRecordBeanList as $questionRecordBean){
                $id = $questionRecordBean->getID();
                self::workTheDetail($questionRecordBean);
            }
        }
    }

    private static function workTheDetail(QuestionDetailBean $questionDetailBean){
        $questionContent    = Format::formatText($questionDetailBean->getQuestionContent());
        $questionAnswer     = Format::formatText($questionDetailBean->getQuestionAnswer());
        $questionAnalysis   = Format::formatText($questionDetailBean->getQuestionAnalysis());

        $contentMathMlList  = self::getMathMlTagList($questionContent);
        $answerMathMlList   = self::getMathMlTagList($questionAnswer);
        $analysisMathMlList = self::getMathMlTagList($questionAnalysis);
    }

    private static function getMathMlTagList($text){
        $matchArr = [];
        preg_match_all('/(<math xmlns*?>[\s\S]*?</math>)/', $text, $matchArr);
        $resultData = $matchArr[1];
        return $resultData;
    }
}