<?php

namespace ploanframework\components;

use rrxframework\base\JdbModule;

/**
 * Class Config
 * @package app\modules\ploanframework\components
 * @author wangdj
 */
class Config{
    private static $enums = [];

    /**
     * 初始化模块配置信息
     * @param $moduleName
     * @return array
     * @author wangdj
     */
    public static function init($moduleName){
        defined('HOSTNAME') or define('HOSTNAME', php_uname('n'));
        define('YII_ENABLE_ERROR_HANDLER', true);
        defined('JDB_SERVER') or define('JDB_SERVER', 1);
        defined('JDB_DB') or define('JDB_DB', 2);
        defined('JDB_CONF_FILE') or define('JDB_CONF_FILE', __DIR__ . '/../../../conf/' . $moduleName . '/server.ini');
        $CFG = parse_ini_file(JDB_CONF_FILE, true);
        defined('ENV') or define('ENV', $CFG['env']??'prod');
        JdbModule::setModuleName($moduleName);
        $config = require(__DIR__ . '/../../config/' . $moduleName . '/web.php');
        $baseConfig = require(__DIR__ . '/../config/web.php');

        $config = array_merge($baseConfig, $config);

        foreach($config['params'] as $key => &$value){
            if(isset($value['serverType']) && $value['serverType'] == JDB_SERVER){
                if(!empty($CFG[$key]['domain'])){
                    $value = array_merge($value, $CFG[$key]);
                }
            }elseif(isset($value['serverType']) && $value['serverType'] == JDB_DB){
                if(!empty($CFG[$key]['host'])){
                    $value = array_merge($value, $CFG[$key]);
                }
            }
        }
        unset($value);
        return $config;
    }

    public static function getEnums(){
        if(empty(static::$enums)){
            $moduleName = JdbModule::getModuleName();
            $defaultEnums = require(__DIR__ . '/../constants/enums.php');
            $customEnums = include(__DIR__ . '/../../' . $moduleName . '/constants/enums.php');
            if($customEnums){
                static::$enums = $defaultEnums + $customEnums;
            }else{
                static::$enums = $defaultEnums;
            }
        }

        return static::$enums;
    }
}