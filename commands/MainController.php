<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/12
 * Time: 下午7:29
 */

namespace app\commands;
use app\apis\IDAllocApi;
use app\components\MockData;
use app\services\daemon\SpiderService;
use yii\console\Controller;
use ploanframework\services\passport\PassportService;

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
}