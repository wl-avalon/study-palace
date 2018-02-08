<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/12
 * Time: 下午6:28
 */
namespace app\library;

use app\components\SPLog;

class Request
{
    public static function curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    public static function proxyCurl($url, $processIndex)
    {
        do{
            $selfProxyIP     = Proxy::getSelfIP($processIndex);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, false);
            curl_setopt($ch, CURLOPT_PROXY, $selfProxyIP);
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    // 返回网页内容

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
            if($httpCode != 200 || strpos($result, 'searchBox') === false){
                Proxy::delSelfIP($selfProxyIP);
                curl_close($ch);
                continue;
            }
            curl_close($ch);
        }while($httpCode != 200 || strpos($result, 'searchBox') === false);
        return $result;
    }
}