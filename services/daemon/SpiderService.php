<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/30
 * Time: 下午9:51
 */
namespace app\services\daemon;
use app\components\Format;
use app\library\Request;

class SpiderService
{
    public static function execute(){
        $startUrl   = "http://www.91taoke.com/Juanzi/index/d/1/id";
        $allEnum    = self::getAllEnum($startUrl);
        foreach($allEnum as $xueDuanIndex => $xueKeData){
            $xueKeList = $xueKeData['data'];
            foreach($xueKeList as $xueKeIndex => $banBenData){
                $banBenList = $banBenData['data'];
                foreach($banBenList as $banBenIndex => $moduleData){
                    $moduleList = $moduleData['data'];
                    foreach($moduleList as $moduleIndex => $module){
                        $p = 0;
                        do{
                            usleep(100000);
                            $url = "http://www.91taoke.com/Juanzi/ajaxlist?id={$xueDuanIndex},{$xueKeIndex},{$banBenIndex},{$moduleIndex}&zjid=0&tixing=0&nandu=0&leixing=0&p={$p}";
                            $p++;
                            $questInfo = SpiderService::getQuestion($url);
                            if(empty($questInfo)){
                                break;
                            }
                        }while(true);
                    }
                }
            }
        }
    }

    public static function getAllEnum($url, $nowLevel = 1){
        usleep(100000);
        $response   = Request::curl($url);

        if($nowLevel == 5){
            $tiXingMap  = Format::getTiXingMap($response);
            $nanDuMap   = Format::getNanDuMap($response);
            $nodeList   = Format::getNodeList($response);
            return [
                'nanDuMap'  => $nanDuMap,
                'tiXingMap' => $tiXingMap,
                'nodeList'  => $nodeList,
            ];
        }else{
            $levelList  = Format::getLevelMap($response, $nowLevel);
        }
        $record = [];
        foreach($levelList as $level => $levelValue){
            if($level == 5){
                return $record;
            }
            if($level == 11){
                return $record;
            }
            if($nowLevel == 1){
                $urlTemp = rtrim($url, '/') . "/{$level}";
            }else{
                $urlTemp = "{$url},{$level}";
            }
            echo "{$level},{$levelValue}\n";
            $record[$level] = [
                'key'   => $level,
                'value' => $levelValue,
                'data'  => self::getAllEnum($urlTemp, $nowLevel + 1),
            ];
        }
        return $record;
    }

    public static function getQuestion($url){
        $response   = Request::curl($url);
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
}