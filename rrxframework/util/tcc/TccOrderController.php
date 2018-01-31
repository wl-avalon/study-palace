<?php
namespace rrxframework\util\tcc;
use rrxframework\base\JdbLog;
use rrxframework\util\tcc\TccOrderBaseImpl;

class TccOrderController{
  public $obj = null;
  
  public function __construct($objImpl){
    if(!$objImpl instanceof TccOrderBaseImpl){
      JdbLog::warning("objImpl not instanceof TccOrderBaseImpl");
      return null;
    }
    
    $this->obj = $objImpl;
  }
  
  public function execute(array $requestData){
    $this->obj->beginTranscation();
    try{
        $this->obj->lockOrder();
        $this->obj->doTranscation($requestData);
        $this->obj->commitTranscation();
    }catch (\Exception $e){
        $this->obj->rollbackTranscation($e->getCode(), $e->getMessage());
    }
    
    $ret = $this->makeResult($this->obj->errno, $this->obj->errmsg, $this->obj->data);
    return $ret;
  }

  public function makeResult($errno, $errmsg, $data){
      $ret = [
          'errno' => $errno,
          'errmsg' => $errmsg,
          'data' => $data,
      ];
      return $ret;
  }
}

?>