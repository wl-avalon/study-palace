<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/30
 * Time: 下午9:51
 */
namespace app\services\daemon;
use app\apis\IDAllocApi;
use app\components\Format;
use app\components\SPLog;
use app\constants\QuestionDetailBeanConst;
use app\library\Request;
use app\models\beans\NodeListBean;
use app\models\question\QuestionDetailModel;
use app\models\questionConst\NodeListModel;

class SpiderService
{
    public static function createNodeConst($allEnum){
        $gradeKey       = 0;
        $subjectKey     = 0;
        $versionKey     = 0;
        $moduleKey      = 0;
        $gradeList      = [];
        $subjectList    = [];
        $versionList    = [];
        $moduleList     = [];
        foreach($allEnum as $xueDuanIndex => $xueKeData){
            $gradeChinese   = $xueKeData['value'];
            $gradeList[]    = [
                'parentNode'    => '',
                'key'           => $gradeKey,
                'value'         => $gradeChinese
            ];
            $xueKeList      = $xueKeData['data'];
            foreach($xueKeList as $xueKeIndex => $banBenData){
                $subjectChinese = $banBenData['value'];
                $subjectList[]  = [
                    'gradeNode' => $gradeKey,
                    'key'       => $subjectKey,
                    'value'     => $subjectChinese,
                ];
                $banBenList     = $banBenData['data'];
                foreach($banBenList as $banBenIndex => $moduleData){
                    $versionChinese = $moduleData['value'];
                    $versionList[]  = [
                        'subjectNode'   => $subjectKey,
                        'key'           => $versionKey,
                        'value'         => $versionChinese,
                    ];
                    $mokuaiList = $moduleData['data'];
                    foreach($mokuaiList as $moduleIndex => $module){
                        $moduleChinese  = $module['value'];
                        $moduleList[]   = [
                            'versionNode'   => $versionKey,
                            'key'           => $moduleKey,
                            'value'         => $moduleChinese,
                        ];
                        self::createNodeList($gradeKey, $subjectKey, $versionKey, $moduleKey, $module['data']['nodeList']);
                        $moduleKey++;
                    }
                    $versionKey++;
                }
                $subjectKey++;
            }
            $gradeKey++;
        }
//        exit;

//        foreach($gradeList as $item){
//            $lastNode   = intval($item['parentNode']);
//            $key        = $item['key'];
//            $value      = $item['value'];
//            echo "['parentNode' => {$lastNode}, 'key' => {$key}, 'value' => '{$value}'],\n";
//        }
//        echo "---------------------------------\n";
//        foreach($subjectList as $item){
//            $lastNode   = intval($item['gradeNode']);
//            $key        = $item['key'];
//            $value      = $item['value'];
//            echo "['parentNode' => {$lastNode}, 'key' => {$key}, 'value' => '{$value}'],\n";
//        }
//        echo "---------------------------------\n";
//        foreach($versionList as $item){
//            $lastNode   = intval($item['subjectNode']);
//            $key        = $item['key'];
//            $value      = $item['value'];
//            echo "['subjectNode' => {$lastNode}, 'key' => {$key}, 'value' => '{$value}'],\n";
//        }
//        echo "---------------------------------\n";
//        foreach($moduleList as $item){
//            $lastNode   = intval($item['versionNode']);
//            $key        = $item['key'];
//            $value      = $item['value'];
//            echo "['versionNode' => {$lastNode}, 'key' => {$key}, 'value' => '{$value}'],\n";
//        }
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
            if($nowLevel == 1){
                $urlTemp = rtrim($url, '/') . "/{$level}";
            }else{
                $urlTemp = "{$url},{$level}";
            }
            SPLog::log("{$level},{$levelValue}\n");
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

    public static function checkRetry($question, $xueKeChinese){
        $content        = "";
        $content        .= $question['questionRemark'];
//        $content        .= $question['resultRemark']; //暂时没用
        $questionList   = $question['questList'];

        foreach($questionList as $questionItem){
            $content .= $questionItem['question'];
            foreach($questionItem['result'] as $key => $value){
                $content .= "key:{$key},value{$value}";
            }
        }
        $md5 = md5($content);
        $dbName = QuestionDetailBeanConst::$subjectChineseMapToEnum[trim($xueKeChinese)];
        $questionDetailBeanList = QuestionDetailModel::queryQuestionListByMD5($md5, $dbName);
        if($questionDetailBeanList){
            return true;
        }

        foreach($questionDetailBeanList as $questionDetailBean){
            $contentTemp    = "";
            $contentTemp    .= $questionDetailBean->getQuestionRemark();
//            $contentTemp    .= $questionDetailBean->get,
        }
    }

    public static function createNodeList($gradeKey, $subjectKey, $versionKey, $moduleKey, $nodeList){
        $uuidList = explode(',', IDAllocApi::batch(count($nodeList))['data']);
        $i = 0;
        $parentNodeID = "";
        foreach($nodeList as $node){
            if($node['id'] != 1027075){
                continue;
            }
            $nodeBeanData = [
                'uuid'              => $uuidList[$i++],
                'grade'             => $gradeKey,
                'subject'           => $subjectKey,
                'version'           => $versionKey,
                'module'            => $moduleKey,
                'node_key'          => $node['id'],
                'node_value'        => $node['name'],
                'parent_node_id'    => strval($parentNodeID),
            ];
            $parentNodeID = strval($node['id']);
            $nodeListBean = new NodeListBean($nodeBeanData);
            $insertResult = NodeListModel::insertOneRecord($nodeListBean);
            SPLog::log("{$moduleKey} --------------- {$insertResult} \n");
            unset($nodeListBean);
            exit;
        }
    }
}