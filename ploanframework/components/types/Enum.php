<?php

namespace ploanframework\components\format\types;

use ploanframework\components\Config;
use ploanframework\constants\JdbErrors;
use rrxframework\base\JdbException;

/**
 * 枚举格式化工具类
 * Class Enum
 * @package app\modules\ploanframework\components\types
 */
class Enum{
    public static function __callStatic($name, $arguments){
        if(count($arguments) != 1){
            throw new JdbException(JdbErrors::ERR_NO_INNER_FAILED);
        }

        list($value) = $arguments;
        $enums = Config::getEnums();
        if(empty($enums[$name]) || !is_array($enums[$name])){
            throw new JdbException(JdbErrors::ERR_NO_INNER_FAILED);
        }

        if(!array_key_exists($value, $enums[$name])){
            throw new JdbException(JdbErrors::ERR_NO_INNER_FAILED);
        }

        return $value;
    }

    public static function default(){
        throw new JdbException(JdbErrors::ERR_NO_INNER_FAILED);
    }
}