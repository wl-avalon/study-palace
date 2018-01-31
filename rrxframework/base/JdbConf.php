<?php
namespace rrxframework\base;
use Yii;

/**
 * 配置获取类
 * @author igaozh
 *
 */
class JdbConf {
	
	public static function get($name){
		if($name == null){
			return Yii::$app->params;
		}
		
		if(isset(Yii::$app->params[$name])){
			return Yii::$app->params[$name];
		} 

        if (isset(Yii::$app->params['service'][$name])) {
            return Yii::$app->params['service'][$name];
        }

		return null;
	}
}