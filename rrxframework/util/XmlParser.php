<?php
namespace rrxframework\util;

/**
 * IfhXmlParser: A class to convert XML to array in PHP
 *
 * @author hongcq@jiedaibao.com
 * @since V1.5
 */
class XmlParser {

	/**
	 * XML转成数组
	 * 
	 * @param string $strXml XML标准格式字符串
	 * @return array
	 */
	public static function xml2array ($strXml) {
		$strReg = "/<(\\w+)[^>]*?>([\\x00-\\xFF]*?)<\\/\\1>/";
		if (preg_match_all ($strReg, $strXml, $matches)) {
			$intCount = count($matches[0]);
			$arrRet = array();
			for($i = 0; $i < $intCount; $i ++) {
				
				$key = $matches [1][$i];
				$val = self::xml2array($matches[2][$i]); // 递归
				if (!empty($val)) {
					$val = self::cleanVal($val);
				}
				
				if (array_key_exists ($key, $arrRet )) {
					if (is_array($arrRet[$key])) {
						if (!array_key_exists ( 0, $arrRet[$key])) {
							$arrRet [$key] = array($arrRet [$key]);
						}
					} else {
						$arrRet [$key] = array($arrRet [$key]);
					}
					$arrRet[$key][] = $val;
				} else {
					$arrRet[$key] = $val;
				}
			}
			return $arrRet;
		} else {
			return $strXml;
		}
	}
	
	/**
	 * 清理XML里的值
	 * 
	 * @param string $strVal
	 * @return string
	 */
	private static function cleanVal($strVal) {
		
		if (!is_string($strVal)) {
			return $strVal;
		}
		
		$strReg = '/<\!\[CDATA\[(.*?)\]\]>/ui';
		$intMc = preg_match($strReg, $strVal, $matches);
		$strRet = htmlspecialchars_decode($strVal);
		if ($intMc > 0) {
			$strRet = isset($matches[1]) ? $matches[1] : "";
		}
		
		return $strRet;
	}
	
	/**
	 * XML转成数组,DOMDocument
	 *
	 * @param string $strXml XML标准格式字符串
	 * @param string $strItem XML标准格式元素字符串
	 * @param arr $arrPropertys 属性
	 * @return array
	 */
	public static function xml2arrayByDom ($strXml,$strItem,$arrPropertys) {
		$dom = new \DOMDocument();
		$dom->loadXML($strXml);
		$itemList = $dom->getElementsByTagName($strItem);//获取所有item标签列表
		$len = $itemList->length;//获取列表中item标签的数量
		$arrRet = array();
		for($i=0;$i<$len;$i++) {//遍历标签
			$item  = $itemList->item($i);//获取列表中单条记录
			$arrRet[$i] = array();
			foreach($arrPropertys as $property){
				$strValue = $item->getAttribute($property);//获取属性值
				$arrRet[$i][$property] = $strValue;
			}
		}
		
		return $arrRet;
	}
	
}
