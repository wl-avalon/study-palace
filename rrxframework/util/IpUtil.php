<?php
namespace rrxframework\util;

class IpUtil {
    public static function getClientIp() {
        $uip = '';

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], 'unknown')) {
            $uip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            strpos($uip, ',') && list($uip) = explode(',', $uip);
        } else if (!empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], 'unknown')) {
            $uip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $uip = $_SERVER['REMOTE_ADDR'];
        }

        return $uip;
    }

    public static function checkInnerIp($ip) {
    	//TODO: 服务器内网100网段，不属于ip的局域网网段，暂时返回true
        return true;

        if ($ip == '127.0.0.1') {
            return true;
        }

        // A类
        if (strnatcasecmp($ip, '10.0.0.0')>=0 &&
            strnatcasecmp($ip, '10.255.255.255')<=0) {
            return true;
        }

        // B类
        if (strnatcasecmp($ip, '172.16.0.0')>=0 &&
            strnatcasecmp($ip, '172.31.255.255')<=0) {
            return true;
        }

        // C类
        if (strnatcasecmp($ip, '192.168.0.0')>=0 &&
            strnatcasecmp($ip, '192.168.255.255')<=0) {
            return true;
        }

        return false;
    }
}