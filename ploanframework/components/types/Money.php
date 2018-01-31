<?php

namespace ploanframework\components\format\types;

/**
 * 金额格式化工具类
 * Class Money
 * @package app\modules\ploanframework\components\types
 */
class Money{
    public static function decimal($money){
        $money = intval(strval(round($money, 10) * 100));
        $op = ($money < 0) ? '-' : '';
        $div = $op . abs(intval($money / 100));
        $mod = abs($money % 100);

        if(0 == $mod){
            // 如果余数为0, 不需要加上.00
            return $div . ""; // 转换成字符串
        }else if($mod < 10){
            return $div . ".0" . $mod;
        }else{
            return $div . "." . $mod;
        }
    }

    public static function default($money){
        return static::decimal($money);
    }
}