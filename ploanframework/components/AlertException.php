<?php
/**
 * Created by PhpStorm.
 * User: AndreasWang
 * Date: 2018/1/19
 * Time: 下午3:14
 */

namespace ploanframework\components;

use rrxframework\base\JdbException;

class AlertException extends JdbException{
    private $popUpRouter;

    public function __construct($err_no, $popUpRouter, $data = null){
        $this->popUpRouter = $popUpRouter;
        parent::__construct($err_no, $data, '待确认', null, 0);
    }

    public function getPopUpRouter(){
        return $this->popUpRouter;
    }
}