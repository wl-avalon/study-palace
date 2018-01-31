<?php
namespace rrxframework\util\tcc\config;

class TccConsts{
  
    /** @var int 0:正常 */
    const INFO_OK = 0;

    /** error*/
    const INFO_ERROR = 1;

    /*process data 长度*/
    const MAX_CONTENT_LENGTH = 8000;

    /***/
    const PROCESS_START = 0;
    const PROCESS_PROCESS = 1;
    const PROCESS_END   = 2;

    // 1 => succ, 2 => fail, 3 => lock
    public static $resultSet = array(1,2);

    const PAY_LOCK = 3;
    const PAY_FAIL = 2;
    const PAY_SUCC = 1;

    /*工单轮训的间隔 60*5=300s*/
    const MAX_LOCK_WAIT_TIME = 300;

    /*补偿的时间点 60*1.5=90*/
    const MAX_WAIT_TIME = 90;
    
    /*未拉取到sleep时间*/
    const MAX_IDLE_TIME = 5;

    /*运行时间<5*60=300*/
    const MAX_EXECUTE_TIME = 280;

    /*每次拉取100个*/
    const LIMIT_NUM = 100;
    
    const MONITOR_MAX_TIME_FOR_LOCK = 86400;
    
    const ORDER_STEP_TRY = 0;
    const ORDER_STEP_COMMIT = 1;
    const ORDER_STEP_ROLLBACK = 2;
    
    const ORDER_ERRNO_SUCCESS = 0;//处理成功
    const ORDER_ERRNO_RESOURCE_HANDLE_CONFLICT = 94;//资源处理冲突，如对一个已经commit的资源进行rollback，或者反过来
    const ORDER_ERRNO_RESOURCE_NOT_EXIST = 95;//资源不存在，如对一个没有try的资源进行commit/rollback
    const ORDER_ERRNO_PDO_EXCEPTION = 97;//数据库处理异常
    const ORDER_ERRNO_RESOURCE_REPEAT_HANDLE = 98;//资源重复处理，如对一个已经try/commit/rollback的资源再次进行try
    const ORDER_ERRNO_UNKNOWN = 99;
    
}

?>
