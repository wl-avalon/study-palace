<?php
namespace rrxframework\util;
use Yii;

class BitUtil {
    // 设置操作
    const OPER_SET = '|';
    // 取消操作
    const OPER_UNSET = '^';

    // 操作列表
    static public $oper_list = [
        self::OPER_SET,
        self::OPER_UNSET,
    ];
}