<?php

namespace ploanframework\apis\models;

use ArrayAccess;
use Countable;
use Iterator;
use ploanframework\apis\ApiContext;
use ploanframework\constants\JdbErrors;
use ploanframework\utils\Arr;
use rrxframework\base\JdbException;

/**
 * 存储系统调用的返回值及错误代码
 * Class Response
 * @package app\modules\wapui\models
 * @author wangdj
 */
class Response implements Iterator, ArrayAccess, Countable{
    use ResponseTrait;

    /**
     * @var Request
     */
    private $request;
    private $data;
    private $rawData;
    private $curlInfo;

    private $initFlag;

    private $returnCode;
    private $returnMessage;
    private $returnUserMessage;

    public function init(){
        if(!$this->initFlag){
            $this->rawData = ApiContext::getInstance()->getResponse($this->request);
            $this->curlInfo = $this->rawData['curl_info'];
            unset($this->rawData['curl_info']);
            $this->request->getHandler()->handleResponse($this, $this->rawData);

            $this->initFlag = true;

            if($this->throwWhenFailed && $this->failed()){
                if($this->customException){
                    throw $this->customException;
                }else{
                    throw new JdbException($this->getReturnCode(), null, $this->getReturnUserMessage(), $this->getReturnMessage());
                }
            }

            if($this->failed() && !empty($this->defaultData)){
                $this->data = $this->defaultData;
            }
        }

        foreach($this->callbacks as $key => $call){
            $this->data = $call($this->data);
        }
        $this->callbacks = [];
    }

    /**
     * Response constructor.
     * @param Request $request
     */
    public function __construct($request = null){
        if(empty($request)){
            $this->initFlag = true;
            $this->rawData = [];
            $this->data = [];
            return;
        }

        $this->request = $request;

        $this->initFlag = false;
        $this->throwWhenFailed = false;

        $this->returnCode = JdbErrors::ERR_NO_SUCCESS;
        $this->data = null;
        $this->curlInfo = [];
    }

    public function getReturnCode(){
        $this->init();

        return $this->returnCode;
    }

    public function setReturnCode($returnCode){
        $this->returnCode = $returnCode;
    }

    public function getRequest(){
        return $this->request;
    }

    public function setReturnMessage(string $message){
        $this->returnMessage = '[' . $this->getRequest()->service . '.' . $this->getRequest()->method . ']' . $message;
    }

    public function getReturnMessage(){
        return $this->returnMessage;
    }

    public function setReturnUserMessage(string $message){
        $this->returnUserMessage = $message;
    }

    public function getReturnUserMessage(){
        return $this->returnUserMessage;
    }

    public function getRawData(){
        $this->init();
        return $this->rawData;
    }

    public function getCurlInfo(){
        return $this->curlInfo;
    }

    /**
     * {@inheritDoc}
     * @see Countable::count()
     */
    public function count(){
        $this->init();
        return count($this->data);
    }

    public function setData($data){
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset){
        $this->init();

        return Arr::has($this->data, $offset);
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset){
        $this->init();
        return Arr::get($this->data, $offset);
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value){
        $this->init();

        if(null === $this->data){
            $this->data = [];
        }
        if(is_array($this->data)){
            $this->data[$offset] = $value;
        }
    }

    /**
     * {@inheritDoc}
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($key){
        $this->init();
        if(is_array($this->data)){
            unset($this->data[$key]);
        }
    }

    /**
     * {@inheritDoc}
     * @see Iterator::current()
     */
    public function current(){
        $this->init();
        if(is_array($this->data)){
            return current($this->data);
        }else{
            return null;
        }
    }

    /**
     * {@inheritDoc}
     * @see Iterator::next()
     */
    public function next(){
        $this->init();
        if(is_array($this->data)){
            return next($this->data);
        }else{
            return null;
        }
    }

    /**
     * {@inheritDoc}
     * @see Iterator::key()
     */
    public function key(){
        $this->init();
        if(is_array($this->data)){
            return key($this->data);
        }else{
            return null;
        }
    }

    /**
     * {@inheritDoc}
     * @see Iterator::valid()
     */
    public function valid(){
        $this->init();
        if(is_array($this->data)){
            return key($this->data) !== null;
        }else{
            return false;
        }
    }

    /**
     * {@inheritDoc}
     * @see Iterator::rewind()
     */
    public function rewind(){
        if(is_array($this->data)){
            return reset($this->data);
        }else{
            return null;
        }
    }

    /**
     * @param array $value
     * @return Response
     */
    public function append(array $value){
        $this->init();

        if(is_array($this->data)){
            $this->data[] = $value;
        }else{
            $this->data = $value;
        }

        return $this;
    }
}