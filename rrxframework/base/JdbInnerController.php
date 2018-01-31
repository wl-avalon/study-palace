<?php
namespace rrxframework\base;

/**
 * 借贷宝控制器基类
 * 
 * @author gaozh@jiedaibao.com
 */
class JdbInnerController extends JdbController
{
	const OPT_OPERATION_KEY = "RRX-OPERATION-SECRET";
    
	
    /**
     * 请求初始化
     * 
     * @see \yii\base\Object::init()
     */
    public function init() {
        parent::init();
    }
    
    public function checkOPTKey($key){
    	return self::OPT_OPERATION_KEY === $key;
    }
    
}