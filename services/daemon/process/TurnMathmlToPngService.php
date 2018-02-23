<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/23
 * Time: 上午10:24
 */

namespace app\services\daemon\process;


use app\components\Format;
use app\components\PackageParams;
use app\models\beans\QuestionDetailBean;
use app\models\question\QuestionDetailModel;

class TurnMathmlToPngService
{
    const JEUCLID_BIN = '/home/saber/jeuclid-3.1.9/bin/mml2xxx';

    public static function execute($processName, $id = 0){
        if(!is_dir(PackageParams::IMAGE_DIR_PATH)){
            mkdir(PackageParams::IMAGE_DIR_PATH);
        }
        while(true){
            $questionRecordBeanList = QuestionDetailModel::queryContainMathMlDetailList($id, $processName);
            if(empty($questionRecordBeanList)){
                break;
            }

            foreach($questionRecordBeanList as $questionRecordBean){
                $id = $questionRecordBean->getID();
                $questionDetailBean = self::workTheDetail($questionRecordBean);
                QuestionDetailModel::updateDetailContentAndAnswerAndAnalysis($questionDetailBean, $processName);
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

        $questionContent    = self::delMathMlTag($questionContent);
        $questionAnswer     = self::delMathMlTag($questionAnswer);
        $questionAnalysis   = self::delMathMlTag($questionAnalysis);
        if(empty($questionContent) && empty($questionAnswer) && empty($questionAnalysis)){
            return $questionDetailBean;
        }else{
            $dirPath = PackageParams::IMAGE_DIR_PATH . "/" . $questionDetailBean->getUuid();
            if(!is_dir($dirPath)){
                mkdir($dirPath);
            }
        }

        foreach($contentMathMlList as $index => $contentMathMl){
            $mathMlFileName = PackageParams::getContentMathMlFileName($dirPath, $index);
            $pngFileName    = PackageParams::getContentPNGFileName($dirPath, $index);
            self::createMathMlFile($mathMlFileName, $contentMathMl);
            self::createPngFile($mathMlFileName, $pngFileName);
            unlink($mathMlFileName);
        }

        foreach($answerMathMlList as $index => $contentMathMl){
            $mathMlFileName = PackageParams::getAnswerMathMlFileName($dirPath, $index);
            $pngFileName    = PackageParams::getAnswerPNGFileName($dirPath, $index);
            self::createMathMlFile($mathMlFileName, $contentMathMl);
            self::createPngFile($mathMlFileName, $pngFileName);
            unlink($mathMlFileName);
        }

        foreach($analysisMathMlList as $index => $contentMathMl){
            $mathMlFileName = PackageParams::getAnalysisMathMlFileName($dirPath, $index);
            $pngFileName    = PackageParams::getAnalysisPNGFileName($dirPath, $index);
            self::createMathMlFile($mathMlFileName, $contentMathMl);
            self::createPngFile($mathMlFileName, $pngFileName);
            unlink($mathMlFileName);
        }
        $questionDetailBean->setQuestionContent($questionContent);
        $questionDetailBean->setQuestionAnswer($questionAnswer);
        $questionDetailBean->setQuestionAnalysis($questionAnalysis);
        return $questionDetailBean;
    }

    private static function getMathMlTagList($text){
        $matchArr = [];
        preg_match_all('/(<math xmlns[^>]*?>[\s\S]*?<\/math>)/', $text, $matchArr);
        $resultData = $matchArr[1];
        return $resultData;
    }

    private static function delMathMlTag($text){
        $text = preg_replace('/(<math xmlns[^>]*?>[\s\S]*?<\/math>)/', '{math-ml-image}', $text);
        return $text;
    }

    private static function createMathMlFile($fileName, $contentMathMl){
        $file = fopen($fileName, 'w');
        fwrite($file, $contentMathMl);
        fclose($file);
    }

    private static function createPngFile($mathMlFileName, $pngFileName){
        exec(self::JEUCLID_BIN . " {$mathMlFileName} {$pngFileName} -fontSize 42");
    }
}