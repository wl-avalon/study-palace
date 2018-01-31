<?php
namespace rrxframework\base;

abstract class JdbServiceBase {

	/**
	 * 执行服务前调用的通用业务逻辑，返回false则阻断后续执行
	 * @param string 服务名
	 * @param array $arrInput
	 * @return bool
	 */
	protected abstract function preCall($method_name,array $arrInput);
	
	/**
	 * 执行服务后调用的通用业务逻辑
	 * @param string 服务名
	 * @param array $arrInput service的输入参数数组
	 * @param mix $mixOutput service的输出
	 * @return mix 如果返回值非bool类型且非空，则返回值将替代$mixOutput输出
	 */
	protected abstract function postCall($method_name,array $arrInput,$mixOutput);
	
    public function invoke($method_name,$method_params,$encoding = "utf-8",$data_format = "json") {
		if(empty($method_name) || empty($method_params)){
			return false;
		}
		
		if(!method_exists($this, $method_name)){
			return false;
		}
		
		if($this->preCall($method_name, $method_params) === false){
			return false;
		}
		$output = $this->$method_name($method_params);
		$post_output = $this->postCall($method_name, $method_params, $output);
		if(!is_bool($post_output) && !empty($post_output)){
			$output = $post_output;
		}
		return $output;
    }
    
}
