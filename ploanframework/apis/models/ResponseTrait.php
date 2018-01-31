<?php

namespace ploanframework\apis\models;

use ploanframework\constants\JdbErrors;
use ploanframework\utils\Arr;
use rrxframework\base\JdbException;
use rrxframework\base\JdbLog;
use rrxframework\base\JdbModule;

trait ResponseTrait{
    private $isFailed = null;
    /**
     * @var array
     */
    private $failedOn = [];

    private $callbacks = [];

    private $customException;

    private $throwWhenFailed;

    private $defaultData = null;

    private $ignoreReturnCode = [];


    /**
     * @return bool
     */
    public function success(){
        return !$this->failed();
    }

    /**
     * @return bool
     */
    public function failed(){
        $this->init();
        if(empty($this->isFailed)) {
            $this->isFailed = false;
            if ($this->returnCode != JdbErrors::ERR_NO_SUCCESS && !in_array($this->returnCode, $this->ignoreReturnCode)) {
                $this->isFailed = true;
            }

            foreach ($this->failedOn as $on) {
                $this->returnCode = $on($this->data);
                if ($this->returnCode != JdbErrors::ERR_NO_SUCCESS) {
                    $this->isFailed = true;
                }
            }

            if ($this->isFailed) {
                $module = JdbModule::getModuleName();
                /** @var Request $request */
                $request = $this->request;
                JdbLog::warning("[ApiContext] msg[{$module}_call_{$this->request->service}_return_error] url[{$request->getUrl()}] params[" . json_encode($request->getParams()) . "] return[" . json_encode($this->getRawData()) . "] retry[{$request->retryTimes}]");
            }
        }
        return $this->isFailed;
    }

    /**
     * @param int $returnCode
     * @param string $returnMessage
     * @return Response|ResponseTrait
     * @throws JdbException
     */
    public function throwWhenFailed($returnCode = null, $returnMessage = null){
        $this->throwWhenFailed = true;
        if(!empty($returnCode)){
            $this->customException = new JdbException($returnCode, null, $returnMessage);
        }

        if($this->getRequest()->curlMode == 'sync' && $this->failed()){
            if($this->customException){
                throw $this->customException;
            }else{
                throw new JdbException($this->getReturnCode(), null, $this->getReturnUserMessage(), $this->getReturnMessage());
            }
        }
        return $this;
    }

    /**
     * @param callable $condition
     * @param int $code
     * @return Response|mixed $this
     */
    public function failedOn(callable $condition, $code = JdbErrors::ERR_NO_INNER_FAILED){
        $this->failedOn[] = static function($data) use ($condition, $code){
            if($condition($data)){
                return $code;
            }else{
                return JdbErrors::ERR_NO_SUCCESS;
            }
        };

        return $this;
    }

    /**
     * @return array|mixed
     */
    public function toArray(){
        $this->init();

        return $this->data;
    }

    /**
     * @param array|mixed $value
     * @return Response|mixed
     */
    public function default($value){
        $this->defaultData = $value;

        return $this;
    }

    /**
     * @param string|callable $selector
     * @return Response|mixed $this
     */
    public function select($selector){
        if(is_string($selector)){
            $this->callbacks[] = static function($data) use ($selector){
                return Arr::get($data, $selector);
            };
        }elseif(is_array($selector)){
            if(Arr::isAssoc($selector)){
                $this->callbacks[] = static function($data) use ($selector){
                    $result = [];
                    foreach($selector as $key => $value){
                        $result[$key] = Arr::get($data, $value);
                    }
                    return $result;
                };
            }else{
                $this->callbacks[] = static function($data) use ($selector){
                    $result = [];
                    foreach($selector as $value){
                        $result[] = Arr::get($data, $value);
                    }
                    return $result;
                };
            }
        }elseif(is_callable($selector)){
            $this->callbacks[] = static function($data) use ($selector){
                return $selector($data);
            };
        }

        return $this;
    }

    public function where(callable $where){
        $this->callbacks['where'] = function($data) use ($where){
            $result = [];
            foreach($data as $key => $value){
                if($where($value)){
                    $result[$key] = $value;
                }
            }
            return $result;
        };

        return $this;
    }

    /**
     * @param callable $relay
     * @return $this
     */
    public function relay(callable $relay){
        $this->callbacks[] = static function($data) use ($relay){
            return $relay($data);
        };

        return $this;
    }

    /**
     * 对指定returnCode，不做错误判定。
     * @param array ...$returnCodes 认为成功的returnCode
     * @return $this
     */
    public function ignore(...$returnCodes){
        $this->ignoreReturnCode = $returnCodes;

        return $this;
    }
}