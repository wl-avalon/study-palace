<?php
namespace rrxframework\util;

/**
 * 时间工具类
 * 
 * @author hongcq
 */
class TimeUtil
{
    /**
     * 获取容易理解的时间表述串
     * 
     * @param int $seconds
     * @return string
     */
    public static function getHumanTime($seconds) {
        $now = time();
        $diff = $seconds - $now;
        $ret = '';
        if ($diff > 86400) {
            $days = floor($diff / 86400);
            $ret = $days . "天";
        } else if ($diff > 3600) {
            $hour = floor($diff / 3600);
            $ret = $hour . "小时";
        } else if ($diff > 60) {
            $min = floor($diff / 60);
            $ret = $min . "分钟";
        } else {
            $ret = $diff . "秒";
        }
        return $ret;
    }
}