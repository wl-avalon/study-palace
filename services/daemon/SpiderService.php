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
        foreach($allEnum as $gradeKey => $xueKeData){
            $xueKeList      = $xueKeData['data'];
            foreach($xueKeList as $subjectKey => $banBenData){
                $banBenList     = $banBenData['data'];
                foreach($banBenList as $versionKey => $moduleData){
                    $mokuaiList = $moduleData['data'];
                    foreach($mokuaiList as $moduleKey => $module){
                        self::createNodeList($gradeKey, $subjectKey, $versionKey, $moduleKey, $module['data']['nodeList']);
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

    public static function createNodeList($gradeKey, $subjectKey, $versionKey, $moduleKey, $nodeList){
        if(count($nodeList) <= 0){
            return;
        }
        $uuidList = explode(',', IDAllocApi::batch(count($nodeList))['data']);
        $i = 0;
        $parentNodeID = "";
        foreach($nodeList as $node){
            $nodeBeanData = [
                'uuid'              => $uuidList[$i++],
                'grade'             => intval($gradeKey),
                'subject'           => intval($subjectKey),
                'version'           => intval($versionKey),
                'module'            => intval($moduleKey),
                'node_key'          => intval($node['id']),
                'node_value'        => $node['name'],
                'parent_node_id'    => intval($parentNodeID),
            ];
            $parentNodeID = strval($node['id']);
            $nodeListBean = new NodeListBean($nodeBeanData);
            $insertResult = NodeListModel::insertOneRecord($nodeListBean);
            SPLog::log("{$node['id']}---------------{$insertResult}");
            unset($nodeListBean);
        }
    }
}