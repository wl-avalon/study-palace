<?php
namespace rrxframework\util;
use Yii;

/**
 * 图像工具类
 * 
 * @author gaozh
 */
class ImageUtil
{
	const IMAGE_URL_PREFIX = "http://jdbserver.b0.upaiyun.com/images/";
	
	const THUMBNAIL_PERCENT_25_SUFFIX = "!percent25";
	const THUMBNAIL_FIXED_60_SUFFIX = "!fixed60";
	const THUMBNAIL_FIXED_80_SUFFIX = "!fixed80";
	const THUMBNAIL_FIXED_120_SUFFIX = "!fixed120";
	
	/**
	 * 将数据库中的单张图片拼接图片url前缀
	 */
	public static function getImageUrlFromDbImage($image) {
		if (empty($image)) {
			return "";
		}
		return self::IMAGE_URL_PREFIX . $image;
	}
	
	public static function getImageThumbnailUrlFromDbImage($image, $w = 0){
		if (empty($image)) {
			return "";
		}
		
		return self::IMAGE_URL_PREFIX . $image . self::getImgSuffix($w);
	}
	
	public static function getImgSuffix($w){
		$w = intval($w);
		if($w == 0){
			return self::THUMBNAIL_PERCENT_25_SUFFIX;
		}
		if($w < 700){
			return self::THUMBNAIL_FIXED_60_SUFFIX;
		}
		if($w <= 1200){
			return self::THUMBNAIL_FIXED_80_SUFFIX;
		}
		return self::THUMBNAIL_FIXED_120_SUFFIX;
	}
}




