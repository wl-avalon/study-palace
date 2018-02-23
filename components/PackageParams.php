<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/2/23
 * Time: 下午3:57
 */

namespace app\components;


class PackageParams
{
    const IMAGE_DIR_PATH = '/home/saber/webroot/image';

    public static function getContentMathMlFileName($dirPath, $index){
        return "{$dirPath}/content_{$index}.mml";
    }

    public static function getAnswerMathMlFileName($dirPath, $index){
        return "{$dirPath}/answer_{$index}.mml";
    }

    public static function getAnalysisMathMlFileName($dirPath, $index){
        return "{$dirPath}/analysis_{$index}.mml";
    }

    public static function getContentPNGFileName($dirPath, $index){
        return "{$dirPath}/content_{$index}.png";
    }

    public static function getAnswerPNGFileName($dirPath, $index){
        return "{$dirPath}/answer_{$index}.png";
    }

    public static function getAnalysisPNGFileName($dirPath, $index){
        return "{$dirPath}/analysis_{$index}.png";
    }
}