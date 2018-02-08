<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/5
 * Time: 下午6:44
 */

namespace app\library;


use app\components\SPLog;
use app\services\daemon\spider\GetProxyIPListService;

class Proxy
{
    private static $selfIpList  = [];
    private static $nowSelfIP   = "";
    private static $checkProxyIPOption = [
        CURLOPT_URL             => 'www.baidu.com',     //请求的网址
        CURLOPT_HEADER          => 1,                   //返回http头信息
        CURLOPT_NOBODY          => 1,                   //不返回html的body
        CURLOPT_RETURNTRANSFER  => 1,                   //返回数据流,不直接输出
        CURLOPT_TIMEOUT         => 1,                   //超时时长,1秒
    ];

    public static function getSelfIP($processIndex){
        if(!empty(self::$nowSelfIP) && in_array(self::$nowSelfIP, self::$selfIpList)){
            return self::$nowSelfIP;
        }
        if(empty(self::$selfIpList)){
            do{
                self::getSelfProxyIPList($processIndex);
            }while(empty(self::$selfIpList));
            SPLog::log("当前可用IP:" . implode(',', self::$selfIpList));
        }
        $maxIndex = count(self::$selfIpList) - 1;
        $index = rand(0, $maxIndex);
        self::$nowSelfIP = self::$selfIpList[$index];
        return self::$nowSelfIP;
    }

    public static function delSelfIP($ipPort){
        $key = array_search($ipPort, self::$selfIpList);
        array_splice(self::$selfIpList, $key, 1);
        SPLog::log("{$ipPort}请求失败,剩余" . count(self::$selfIpList));
    }

    public static function getSelfProxyIPList($allIPList){
        $selfIPList = [];
        while(count($allIPList) > 0){
            $mh = curl_multi_init();
            $proxyIPList = array_splice($allIPList, 0, 200);
            //1 设置请求线程的参数
            $chSet = [];
            foreach($proxyIPList as $proxyIP){
                $chSet[$proxyIP] = curl_init();
                $option = self::$checkProxyIPOption;
                $option[CURLOPT_PROXY] = $proxyIP;
                curl_setopt_array($chSet[$proxyIP], $option);
                curl_multi_add_handle($mh, $chSet[$proxyIP]);
            }

            //2 开始请求
            do {
                $mrc = curl_multi_exec($mh, $active);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            while ($active && $mrc == CURLM_OK) {
                if (curl_multi_select($mh) != -1) {
                    do {
                        $mrc = curl_multi_exec($mh, $active);
                    } while ($mrc == CURLM_CALL_MULTI_PERFORM);
                }
            }

            //3 获取请求结果
            foreach($chSet as $ipPort => $ch){
                $requestCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_multi_remove_handle($mh, $chSet[$ipPort]);
                if($requestCode != "200"){
                    continue;
                }
                $selfIPList[] = $ipPort;
            }
            curl_multi_close($mh);
        }
        return $selfIPList;
    }
}