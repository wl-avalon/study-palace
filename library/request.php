<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/12
 * Time: 下午6:28
 */
namespace app\library;

class Request
{
    public static function curl($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }
}