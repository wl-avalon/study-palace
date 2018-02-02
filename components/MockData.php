<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/23
 * Time: 下午9:17
 */

namespace app\components;


class MockData
{
    public static function getTestRecord(){
        $file = fopen(dirname(__DIR__)."/mock_data", 'r');
        $content = fgets($file);
        return json_decode($content, true);
    }

    public static function getTestResponse(){
        $file = fopen(dirname(__DIR__)."/test", 'r');
        $content = fgets($file);
        return json_decode($content, true);
    }
}