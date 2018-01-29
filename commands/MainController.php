<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/12
 * Time: 下午7:29
 */

namespace app\commands;
use app\components\Format;
use app\components\MockData;
use app\library\Request;
use yii\console\Controller;

class MainController extends Controller
{
    public function actionIndex($startUrl){
        set_time_limit(0);
//        $requestUrl = $startUrl;
//        $record = $this->curlOne($requestUrl);
        $record = MockData::getTestRecord();
        $record = json_decode($record, true);
        foreach($record as $xueDuanIndex => $xueKeData){
            $xueKeList = $xueKeData['data'];
            foreach($xueKeList as $xueKeIndex => $banBenData){
                $banBenList = $banBenData['data'];
                foreach($banBenList as $banBenIndex => $moduleData){
                    $moduleList = $moduleData['data'];
                    foreach($moduleList as $moduleIndex => $module){
                        $this->getQuestion($xueDuanIndex, $xueKeIndex, $banBenIndex, $moduleIndex, $module['data']);
                    }
                }
            }
        }
    }

    public function curlOne($url, $nowLevel = 1){
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
                'data'  => $this->curlOne($urlTemp, $nowLevel + 1),
            ];
        }
        return $record;
    }

    public function getQuestion($xueDuan, $xueKe, $banBen, $moduleIndex, $moduleData){
        $url = "http://www.91taoke.com/Juanzi/ajaxlist?id={$xueDuan},{$xueKe},{$banBen},{$moduleIndex}&zjid=0&tixing=0&nandu=0&leixing=0";
//        $url = "http://www.91taoke.com/Juanzi/ajaxlist?id=3,9,27,1000001&zjid=0&tixing=114&nandu=0&search=&leixing=0&xuekename=语文";
//        $url = "http://www.91taoke.com/Juanzi/ajaxlist?id=3%2C11%2C37%2C1000037&zjid=0&tixing=0&nandu=0&leixing=0&xuekename=%E6%95%B0%E5%AD%A6";
        $response       = Request::curl($url);
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
        echo json_encode($questionListRes);exit;
        return $questionListRes;
    }
}