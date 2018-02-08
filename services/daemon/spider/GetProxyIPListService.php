<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/7
 * Time: 下午4:06
 */

namespace app\services\daemon\spider;


use app\components\SPLog;
use app\library\Request;

class GetProxyIPListService
{
    public static function getProxyIPList($daiLiName){
        $ipPortList = [];
        $i = 1;
        while(count($ipPortList) < 1000){
            $ipPortListTemp = self::getSpiderIPAndPort($daiLiName, $i++);
            $ipPortList     = array_merge($ipPortList, $ipPortListTemp);
            $ipPortList     = array_unique($ipPortList);
            sleep(1);
        }
        return $ipPortList;
    }

    private static function getSpiderIPAndPort($daiLiName, $i){
        $html = self::getDaiLi($daiLiName, $i);
        $temp = "";
        switch($daiLiName){
            case 'kuai':{
                $temp = '/<td data-title="IP">((?:(?:25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d)\.){3}(?:25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d))<\/td>[\s\S]*?<td data-title="PORT">([0-9]*?)<\/td>/';
                break;
            }
            case 'yun':{
                $temp = '/<td>((?:(?:25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d)\.){3}(?:25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d))<\/td>[\s\S]*?<td>([0-9]*?)<\/td>/';
                break;
            }
            case 'xi_ci':{
                $temp = '/<td>((?:(?:25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d)\.){3}(?:25[0-5]|2[0-4]\d|1\d{2}|[1-9]?\d))<\/td>[\s\S]*?<td>([0-9]*?)<\/td>/';
                break;
            }
        }
        $matchArr = [];
        preg_match_all($temp, $html, $matchArr);
        $ipList     = $matchArr[1] ?? [];
        $portList   = $matchArr[2] ?? [];
        $i = 0;
        $ipPortList = [];
        foreach($ipList as $ip){
            if(!isset($portList[$i])){
                continue;
            }
            $port           = $portList[$i];
            $ipPort         = trim($ip) . ":" . trim($port);
            $ipPortList[]   = $ipPort;
        }
        return $ipPortList;
    }

    private static function getDaiLi($name, $i){
        $url = "";
        switch($name){
            case 'kuai':{
                $url = "https://www.kuaidaili.com/free/inha/{$i}/";
                break;
            }
            case 'yun':{
                $url = "http://www.ip3366.net/free/?stype=1&page={$i}";
                break;
            }
            case 'xi_ci':{
                $url = "http://www.xicidaili.com/nn/{$i}";
                break;
            }
        }
        return Request::curl($url);
    }

    public static function getMiPuIpList(){
        $orderID = '861316112088054181';
        $url = "https://proxyapi.mimvp.com/api/fetchopen.php?orderid={$orderID}&num=5000&http_type=1&anonymous=5&filter_hour=1&request_method=1&result_sort_field=4&result_format=json";
        $response = Request::curl($url);
        $response = json_decode($response, true);
        if(isset($response['code']) || !is_array($response['result'])){
            SPLog::warning($response['code_msg']);
            return [];
        }
        $ipList = array_column($response['result'], 'ip:port');
        return $ipList;
    }
}