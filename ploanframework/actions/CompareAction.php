<?php
/**
 * author : wangzhengjun
 * QQ     : 694487069
 * phone  : 15801450732
 * Email  : wangzjc@jiedaibao.com
 * Date   : 17/6/12
 */
namespace ploanframework\actions;

use ploanframework\apis\ApiContext;
use ploanframework\apis\models\Response;
use ploanframework\apis\models\ResponseTrait;
use ploanframework\utils\Diff;
use rrxframework\base\JdbException;
use rrxframework\base\JdbLog;

abstract class CompareAction extends BaseAction
{
    /**
     * 获取http原接口返回数据
     * @return Response|ResponseTrait
     */
    protected function getSourceApiRet()
    {
        $params = $this->get();

        return ApiContext::get('tradeui', $this->getSourceApiName(), $params);
    }

    protected abstract function getSourceApiName();

    public function diffResponse($errorCode, $data = null, $errorUserMessage = null, $errorSystemMessage = null)
    {
        $rawHttpRet = $this->getSourceApiRet();
        if(!is_null($rawHttpRet))
        {
            if($rawHttpRet->success()){
                $this->compareData($rawHttpRet->toArray(), $data);
            }else{
                $oldResponse = $this->constructResponse($rawHttpRet->getReturnCode(), $rawHttpRet->toArray(), $rawHttpRet->getReturnUserMessage(), $rawHttpRet->getReturnMessage());
                $newResponse = $this->constructResponse($errorCode, $data, $errorUserMessage, $errorSystemMessage);
                $this->compareData($oldResponse, $newResponse);
                throw new JdbException($rawHttpRet->getReturnCode(), null, $rawHttpRet->getReturnUserMessage(), $rawHttpRet->getReturnMessage());
            }
            return $rawHttpRet;
        }else{
            JdbLog::addNotice("http_source_null","1");
            return $rawHttpRet;
        }

    }

    protected function compareData($old, $new)
    {
        Diff::compare($old, $new, $this->getSourceApiName());
    }

    private function constructResponse($errorCode, $data = null, $errorUserMessage = null, $errorSystemMessage = null){
        return [
            'error' => [
                'returnCode'        => $errorCode,
                'returnMessage'     => $errorSystemMessage,
                'returnUserMessage' => $errorUserMessage
            ],
            'data' => $data
        ];
    }
}