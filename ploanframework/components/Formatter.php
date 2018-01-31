<?php

namespace ploanframework\components;

use ploanframework\constants\JdbErrors;
use ploanframework\utils\Arr;
use rrxframework\base\JdbException;

class Formatter{
    /**
     * 入参校验
     * @param array $params 入参数据
     * @param array $rules 入参规则
     * @throws JdbException
     */
    public static function validate(array $params, array $rules){
        $validator = Validator::make($params, $rules);
        if($validator->fails()){
            $validator->throws();
        }
    }

    /**
     * 返回值格式化 TODO 需要重写
     * @param array|null $data 返回值数据
     * @param array $config 返回值规则
     * @return array
     * @throws JdbException
     */
    public static function format($data, array $config){
        if(empty($data)){
            return $data;
        }

        $ret = [];
        foreach($config as $key => $patten){
            if(is_array($patten) && empty($patten['source'])){
                $ret[$key] = static::format($data, $patten);
                continue;
            }

            if(empty($patten['source'])){
                throw new JdbException(JdbErrors::ERR_NO_UNKNOWN, null, "格式化配置错误, source不能为空");
            }
            if(empty($patten['comment'])){
                throw new JdbException(JdbErrors::ERR_NO_UNKNOWN, null, "格式化配置错误, comment不能为空");
            }

            $value = Arr::get($data, $patten['source']);

            $format = $patten['types']??false;

            if($format && !is_array($format)){
                list($class, $method) = explode(':', $format);
                if(empty($method)){
                    $method = 'default';
                }

                $result = [];
                preg_match_all("/(?:\()(.*)(?:\))/i", $format, $result);
                $params = $result[1][0]??[];
                if(!empty($params)){
                    $params = explode(',', $params);
                }
                $params[] = $value;
                $class = 'ploanframework\components\types\\' . $class;
                $val = call_user_func_array([&$class, $method], $params);
                if(is_int($key)){
                    $ret = $val;
                }else{
                    $ret[$key] = $val;
                }
            }elseif(is_array($format)){
                if(!is_array($value)){
                    throw new JdbException(JdbErrors::ERR_NO_UNKNOWN, null, "格式化配置错误, 返回值中[{$patten['source']}]必须是列表");
                }

                foreach(array_values($value) as $item){
                    $temp = [];
                    foreach($format as $subKey => $subValue){
                        $temp[$subKey] = static::format($item, [$subValue]);
                    }
                    $ret[$key][] = $temp;
                }
            }else{
                if(is_int($key)){
                    $ret = $value;
                }else{
                    $ret[$key] = $value;
                }
            }
        }
        return $ret;
    }

    /**
     * 生成文档
     * @param array $rules 入参规则
     * @param array $format 返回值规则
     */
    public static function generateDoc(array $rules, array $format){

    }

    /**
     * 格式化身份证号,前$front后$behind
     * @param $sIdentity
     * @param int $front
     * @param int $behind
     * @return string
     */
    public static function formatIdentity($sIdentity, $front = 6, $behind = 2){
        return substr($sIdentity, 0, $front) . str_pad("", strlen(substr($sIdentity, $front, -$behind)), "*") . substr($sIdentity, -$behind, $behind);
    }
}