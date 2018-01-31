<?php
namespace rrxframework\components;

use Yii;

class Distance {

    /**
     * @param $longitude1
     * @param $latitude1
     * @param $longitude2
     * @param $latitude2
     * @param int $unit 单位：1米，2千米
     * @param int $decimal 四舍五入精度
     * @return float
     */
    public static function getDistance($longitude1, $latitude1, $longitude2, $latitude2, $unit=1, $decimal=0){

        $EARTH_RADIUS = 6370.996; // 地球半径系数
        $PI = 3.1415926;

        $radLat1 = $latitude1 * $PI / 180.0;
        $radLat2 = $latitude2 * $PI / 180.0;

        $radLng1 = $longitude1 * $PI / 180.0;
        $radLng2 = $longitude2 * $PI /180.0;

        $a = $radLat1 - $radLat2;
        $b = $radLng1 - $radLng2;

        $distance = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1) * cos($radLat2) * pow(sin($b/2),2)));
        $distance = $distance * $EARTH_RADIUS * 1000;

        if($unit==2){
            $distance = $distance / 1000;
        }

        return round($distance, $decimal);
    }
}