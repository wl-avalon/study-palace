<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/12
 * Time: 下午7:29
 */

namespace app\commands;
use app\components\Format;
use app\library\Request;
use yii\console\Controller;

class MainController extends Controller
{
    public function actionIndex($startUrl){
        $requestUrl = $startUrl;
        do{
            $this->curlOne($requestUrl);
            exit;
        }while(true);
    }

    public function curlOne($url, $nowLevel = 1){
        sleep(1);
        $response = Request::curl($url);
        $levelList = Format::getLevelMap($response, $nowLevel);
        if($nowLevel == 4){
            return $levelList;
        }
        $record = [];
        foreach($levelList as $level => $levelValue){
            if($nowLevel == 1){
                $urlTemp = rtrim($url, '/') . "/{$level}";
            }else{
                $urlTemp = "{$url},{$level}";
            }
            echo "{$level}-----{$levelValue}----{$urlTemp}\n";
            $record[$level] = $this->curlOne($urlTemp, $nowLevel + 1);
        }
        return $record;
    }
}