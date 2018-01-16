<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/15
 * Time: 下午9:47
 */
namespace app\components;

use app\models\beans\TopicBean;

class Format
{
    public static function getLevelMap($response, $chooseLevel){
        $matchStr = [];
        preg_match_all("/\B<a[^>]*?aclick\(([1234]),([0-9]*)\)[^>]*?>([^<]*?)<\/a>\B/", $response, $matchStr);
        $tabLevelList   = $matchStr[1];
        $keyList        = $matchStr[2];
        $valueList      = $matchStr[3];
        $tabLevelMap = [];
        foreach($tabLevelList as $index => $level){
            $tabLevelMap[$level][$keyList[$index]] = $valueList[$index];
        }
        return $tabLevelMap[$chooseLevel];
    }
}