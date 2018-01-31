<?php
namespace rrxframework\base;
use Yii;

use rrxframework\base\JdbLog;

class JdbSession {
	static public function getSessionId(){
	    return JdbLog::getLogID();
		session_name('JSESSIONID');
		$sid = session_id();
		if(empty($sid)){
			session_start();
			$sid = session_id();
		}
		return $sid;
	}
}