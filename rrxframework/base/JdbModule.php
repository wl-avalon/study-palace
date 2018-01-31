<?php
namespace rrxframework\base;

use yii;

class JdbModule {
	const DEAULT_MODULE_NAME = "unknown";
    static protected $module_name  = self::DEAULT_MODULE_NAME;

    static public function setModuleName($module_name) {
	self::$module_name = $module_name;
    }

    static public function getModuleName() {
	return self::$module_name;
    }
}
