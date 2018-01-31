<?php
/***************************************************************************
 *
 * Copyright (c) 2017 Rrx. All Rights Reserved
 *
 **************************************************************************/

namespace ploanframework\components;

use ploanframework\constants\JdbColors;

/**
 * @abstract 工具类
 * @author yaodw(@jiedaibao.com)
 * @since 2017年2月14日
 */
class JdbUtil {
    
    private static $time;
    
    /**
     * 获取所有请求参数
     */
    public static function getAllParams(){
        return array_merge(\Yii::$app->request->get(), \Yii::$app->request->post());
    }
    
    /**
     * 数组参数检查，没设置+空
     * @param array $params 入参
     * @param array $needKeys 需要检查的key
     * @return boolean
     */
    public static function checkParamsNotNull($params, $needKeys){
        if (!is_array($needKeys)) {
            return false;
        }
        
        foreach ($needKeys as $key) {
            if (!isset($params[$key])){
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * 获取当前时间
     * 这里为了一些测试场景添加
     */
    public static function getCurrentTime(){
        if (empty(self::$time)) {
            return time();
        } else {
            return self::$time;
        }
    }
    
    /**
     * 设置当前时间
     * 这里为了一些测试场景添加
     */
    public static function setCurrentTime($time){
        self::$time = is_numeric($time) ? $time : null;
    }
    
    /**
     * 获取当前系统时间 毫秒
     * 这里为了一些测试场景添加
     */
    public static function getCurrentTimeMillis(){
        return self::getCurrentTime() * 1000;
    }
    
    /**
     * 生成富文本内容
     * @param array|mixed
     * @return array
     * @author yaodw
     */
    public static function getRichText(){
        $richTextParams = [];
        $args = func_get_args();
    
        if($args != null && count($args) > 0){
            if(is_array($args[0])){
                $richTextParams = $args[0];
            } else {
                if(count($args) >= 1){
                    $richTextParams['text'] = $args[0];
                }
    
                if(count($args) >= 2){
                    $richTextParams['color'] = $args[1];
                }
    
                if(count($args) >= 3){
                    $richTextParams['fontSize'] = $args[2];
                }
            }
        }
    
        $richTextDefault = [
            'type' => 1,
            'text' => '',
            'color' => JdbColors::CONT_D,
            'fontSize' => 14,
            'action' => null,
            'icon' => 0,
        ];
    
        return array_merge($richTextDefault, $richTextParams);
    }
    
    /**
     * 需要关注处理负数问题
     *
     * @param $money
     * @return string
     */
    public static function fmtMoney($money)
    {
        $money = intval(strval(round($money, 10) * 100));
        $op = ( $money < 0)? '-':'';
        $div = $op.abs(intval($money/100));
        $mod = abs($money%100);
    
        if( 0 == $mod ){
            // 如果余数为0, 不需要加上.00
            return $div.""; // 转换成字符串
        }
        else if( $mod < 10 ){
            return $div.".0".$mod;
        }
        else{
            return $div.".".$mod;
        }
    
    }

    public static function convertFenToYuan($fen)
    {
        return self::fmtMoney(floatval($fen/100));
    }


    public static function convertYuanToFen($yuan)
    {
        return intval($yuan *  100 + 0.0001);
    }


    /**
     * 按照二维数组的某个key进行排序
     * @param array $arr 要排序的二维数组
     * @param string $sortKey 指定排序的 key
     * @param string $sortType 排序类型
     * @author yaodw
     * @return array 排序后的数组
     */
    public static function sortMultiArr(array $arr, $sortKey, $sortType) {
        if($arr === null || count($arr) <= 1){
            return $arr;
        }

        $arrSort = [];
        foreach ($arr as $rowId => $row) {
            foreach ($row as $key => $val) {
                if ($sortKey == $key) {
                    $arrSort[$key][$rowId] = $val;
                }
            }
        }

        array_multisort($arrSort[$sortKey], constant($sortType), $arr);
        return $arr;
    }

    public static function getClientVersion(){
        if (defined('CLIENT_VERSION')) {
            return CLIENT_VERSION;
        }

        $clientVersion = \Yii::$app->request->post('clientVersion', '2.8.8');

        define('CLIENT_VERSION', $clientVersion);
        return CLIENT_VERSION;
    }

    public static function filterTransactionListForOldVersion($transactionList){
        $list = [];
        foreach ($transactionList as $transaction){
            $item = $transaction;
            if($transaction['type'] == 76){
                $item['type'] = 73;
            }
            $list[] = $item;
        }

        return $list;
    }

    /**
     * 比较两个时间撮相差多少天
     * @param int $start 时间戳,单位为秒
     * @param int $end  时间戳，单位为秒
     *
     * @return int
     */
    public static function diffDay($start, $end)
    {
        $days =  ($end - $start) / 86400;
        return (integer) $days;
    }
}