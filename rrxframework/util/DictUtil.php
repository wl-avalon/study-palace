<?php
namespace rrxframework\util;
use Yii;

class DictUtil {
	public static $arrDictTimeMap = array();

	public static function setTimeMap($strKey, $intValue){
		$strTempKey = 'Time_'.$strKey;
		if(isset(self::$arrDictTimeMap[$strTempKey])){
			self::$arrDictTimeMap[$strTempKey] = self::$arrDictTimeMap[$strTempKey] + $intValue;
		}else{
			self::$arrDictTimeMap[$strTempKey] = $intValue;
		}
		return true;
	}

	public static function microtimeFloat()
	{
    	list($usec, $sec) = explode(" ", microtime());
    	return ((float)$usec + (float)$sec);
	}
}