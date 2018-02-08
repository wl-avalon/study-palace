<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/12
 * Time: 下午7:29
 */

namespace app\commands;
use app\apis\IDAllocApi;
use app\components\Format;
use app\components\MockData;
use app\components\SPLog;
use app\constants\RedisKey;
use app\library\Proxy;
use app\library\Request;
use app\services\daemon\spider\CreateQuestionService;
use app\services\daemon\spider\GetProxyIPListService;
use app\services\daemon\SpiderService;
use rrxframework\util\RedisUtil;
use yii\console\Controller;

class MainController extends Controller
{
    public function actionIndex($subjectName, $processIndex){
//        GetProxyIPListService::getProxyIPList();exit;
        set_time_limit(0);
//        $startUrl   = "http://www.91taoke.com/Juanzi/index/d/1/id";
//        $record    = SpiderService::getAllEnum($startUrl);
//        echo json_encode($record);
//        exit;
        $record = MockData::getTestRecord();
//        SpiderService::createNodeConst($record);
        CreateQuestionService::foreachQuestionList($record, $subjectName, $processIndex);
    }

    public function actionGetSelfProxyIpList(){
        set_time_limit(0);
        $redis = RedisUtil::getInstance('redis');
        while(true){
            $miPuIPList = GetProxyIPListService::getMiPuIpList();
            $selfIPList = Proxy::getSelfProxyIPList($miPuIPList);
            $redis->sadd(RedisKey::SELF_PROXY_IP_LIST, $selfIPList);
            sleep(5);
        }
    }

    public function actionGetSelfSpiderProxyIpList(){
        set_time_limit(0);
        $redis = RedisUtil::getInstance('redis');
        while(true){
            $kuaiIPList = GetProxyIPListService::getProxyIPList('kuai');
            $yunIPList  = GetProxyIPListService::getProxyIPList('yun');
            $xiCiIPList = GetProxyIPListService::getProxyIPList('xi_ci');
            $ipList     = array_merge($kuaiIPList, $yunIPList, $xiCiIPList);
            $ipList     = array_unique($ipList);
            $selfIPList = Proxy::getSelfProxyIPList($ipList);
            $redis->sadd(RedisKey::SELF_PROXY_IP_LIST, $selfIPList);
            sleep(5);
        }
    }
}