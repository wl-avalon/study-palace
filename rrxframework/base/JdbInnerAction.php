<?php
namespace rrxframework\base;

use rrxframework\base\JdbAction;

/**
 * 借贷宝内部动作基类
 * 
 * @author gaozh@jiedaibao.com
 */
abstract class JdbInnerAction extends JdbAction
{
	const OPT_OPERATION_KEY = "RRX-OPERATION-SECRET";
	
	// 是否需要登陆校验
	protected $check_login = false;
	// 是否需要内网校验
	protected $check_inner = true;
	// 是否校验app_id与app_key（appKey新的校验方案）
	protected $check_app_id_and_app_key = true;
	
	public function checkOPTKey($key){
    	return self::OPT_OPERATION_KEY === $key;
    }
}