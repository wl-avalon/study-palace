<?php
namespace rrxframework\util;
use Yii;

class AppUtil {
    /**
     * APP版本比较
     *
     * 支持2.9.2 2.9.2.0 2.9.2.0.10 无限位数的版本号比较
     *
     * @param $version1
     * @param $version2
     * @return mixed 在第一个版本低于第二个时，返回 -1；如果两者相等，返回 0；第二个版本更低时则返回 1
     */
    static public function compareVersion($version1, $version2) {
        return version_compare($version1, $version2);
    }
}