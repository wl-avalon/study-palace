<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/12
 * Time: 下午7:29
 */

namespace app\commands;
use app\components\MockData;
use app\constants\RedisKey;
use app\library\Proxy;
use app\services\daemon\spider\CreateQuestionService;
use app\services\daemon\spider\GetProxyIPListService;
use rrxframework\util\RedisUtil;
use yii\console\Controller;

class MainController extends Controller
{
    public function actionIndex($subjectName, $processIndex){
        set_time_limit(0);
        $record = MockData::getTestRecord();
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

//        $startUrl   = "http://www.91taoke.com/Juanzi/index/d/1/id";
//        $record    = SpiderService::getAllEnum($startUrl);
//        echo json_encode($record);
//        exit;
