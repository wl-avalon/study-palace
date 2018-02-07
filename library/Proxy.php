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
    private static $checkProxyIPOption = [
        CURLOPT_URL             => 'www.91taoke.com',   //请求的网址
        CURLOPT_HEADER          => 1,                   //返回http头信息
        CURLOPT_NOBODY          => 1,                   //不返回html的body
        CURLOPT_RETURNTRANSFER  => 1,                   //返回数据流,不直接输出
        CURLOPT_TIMEOUT         => 1,                   //超时时长,1秒
    ];

    public static function getSelfIP(){
        if(empty(self::$selfIpList)){
            SPLog::log("开始获取可用的代理IP列表");
            do{
                self::getSelfProxyIPList();
            }while(empty(self::$selfIpList));
            SPLog::log("可用的代理IP列表为:" . json_encode(self::$selfIpList));

        }
        $maxIndex = count(self::$selfIpList) - 1;
        $index = rand(0, $maxIndex);
        return self::$selfIpList[$index];
    }

    public static function delSelfIP($ipPort){
        SPLog::log("IP{$ipPort}请求失败,删除");
        unset(self::$selfIpList[$ipPort]);
    }

    private static function getSelfProxyIPList(){
        SPLog::log("开始获取代理IP列表");
        $ipList = GetProxyIPListService::getProxyIPList();
        SPLog::log("获取代理IP列表结束,列表为:" . implode(',', $ipList));
        while(count($ipList) > 0){
            $mh = curl_multi_init();
            $proxyIPList = array_splice($ipList, 0, 200);
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
                self::$selfIpList[] = $ipPort;
            }
            curl_multi_close($mh);
        }
    }
}