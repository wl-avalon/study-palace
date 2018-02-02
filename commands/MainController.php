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
use app\library\Request;
use app\services\daemon\SpiderService;
use yii\console\Controller;

class MainController extends Controller
{
    public function actionIndex($startUrl){
//        $this->test();exit;
        set_time_limit(0);
//        $a = IDAllocApi::batch(10);
//        var_dump($a);exit;
//        $startUrl   = "http://www.91taoke.com/Juanzi/index/d/1/id";
//        $record    = SpiderService::getAllEnum($startUrl);
//        echo json_encode($record);
//        exit;
        $record = MockData::getTestRecord();
        SpiderService::createNodeConst($record);
    }

    public function test(){
        $response   = Request::curl("http://www.91taoke.com/Juanzi/index/id/3,13,53,1026421");
        Format::getNodeList($response);
    }
}