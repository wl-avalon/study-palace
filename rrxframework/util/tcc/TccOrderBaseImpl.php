<?php
namespace rrxframework\util\tcc;

use rrxframework\util\tcc\models\TccOrderMdl;
use rrxframework\util\tcc\config\TccConsts;
use rrxframework\base\JdbException;
use yii\db\Connection;
use yii\db\Transaction;
use yii\db\IntegrityException;
use rrxframework\base\JdbLog;

abstract class TccOrderBaseImpl{
    public $dbHandler = null;
    public $transcation = null;
    public $sharding = null;
    public $orderID = '';
    public $step = null;//记录本次事务是分布式事务中的步骤
    
    public $errno = null;//记录本次事务操作结果，0=成功，98=资源锁定失败，99=未知错误，其他=用户自定义错误
    public $errmsg = null;
    public $data = null;//存储事务中上下文数据
    
    
    public abstract function doTryTranscation(array $requestData);

    public abstract function doCommitTranscation(array $requestData);
    
    public abstract function doRollbackTranscation(array $requestData);
    
    
    public function __construct($dbHandler, $sharding, $orderID, $step){
        if(!$dbHandler instanceof Connection){
            JdbLog::warning("dbHandler not instanceof Connection");
            return null;
        }
        $this->dbHandler = $dbHandler;
        TccOrderMdl::setdb($dbHandler);
        
        $this->sharding = $sharding;
        $this->orderID = $orderID;
        $this->step = $step;
    }
    
    public function beginTranscation(){
        $transcation = $this->dbHandler->beginTransaction();
        $this->transcation = $transcation;
    }

    public function lockOrder(){
        if($this->step == TccConsts::ORDER_STEP_TRY){
            try{
                TccOrderMdl::createTccOrder($this->sharding, $this->orderID);
            }catch (\yii\db\Exception $e){
                if($e->errorInfo[0] == 23000 && $e->errorInfo[1] == 1062){
                    throw new JdbException(TccConsts::ORDER_ERRNO_RESOURCE_REPEAT_HANDLE, null, "资源已经存在");
                }
                JdbLog::warning("pdo exception:".$e->getMessage());
                throw new JdbException(TccConsts::ORDER_ERRNO_PDO_EXCEPTION, null, "数据库操作失败");
                
            }
        }else if($this->step == TccConsts::ORDER_STEP_COMMIT || $this->step == TccConsts::ORDER_STEP_ROLLBACK){
            $affectRows = TccOrderMdl::updateTccOrderStep($this->sharding, $this->orderID, $this->step);
            if($affectRows <= 0){
                $tccOrder = TccOrderMdl::getTccOrder($this->sharding, $this->orderID);
                if(empty($tccOrder)){
                    JdbLog::warning("tcc order not exist.[sharding=" . $this->sharding . "]"
                        . "[orderID=" . $this->orderID . "][step=" . $this->step . "]");
                    throw new JdbException(TccConsts::ORDER_ERRNO_RESOURCE_NOT_EXIST, null, "资源不存在");
                }else{
                    if($tccOrder['step'] == $this->step){
                        throw new JdbException(TccConsts::ORDER_ERRNO_RESOURCE_REPEAT_HANDLE, null, "资源已经存在");
                    }else{
                        JdbLog::warning("tcc order handle conflict.[sharding=" . $this->sharding . "]"
                            . "[orderID=" . $this->orderID . "][step=" . $this->step . "]");
                        throw new JdbException(TccConsts::ORDER_ERRNO_RESOURCE_HANDLE_CONFLICT, null, "资源处理产生冲突");
                    }
                }
            }
        }
    }
    
    public function commitTranscation(){
        $this->errno = TccConsts::ORDER_ERRNO_SUCCESS;
        $this->errmsg = "success";
        $this->transcation->commit();
    }
    
    public function rollbackTranscation($result, $message){
        $this->errno = $result == TccConsts::ORDER_ERRNO_SUCCESS ? TccConsts::ORDER_ERRNO_UNKNOWN : $result;
        $this->errmsg = $message;
        $this->transcation->rollBack();
    }
    

    /**
     * 事务处理接口，如果需要回滚，请抛异常
     */
    public function doTranscation(array $requestData){
        if($this->step == TccConsts::ORDER_STEP_TRY){
            $this->doTryTranscation($requestData);
        }else if($this->step == TccConsts::ORDER_STEP_COMMIT){
            $this->doCommitTranscation($requestData);
        }else if($this->step == TccConsts::ORDER_STEP_ROLLBACK){
            $this->doRollbackTranscation($requestData);
        }
    }
    
}

?>
