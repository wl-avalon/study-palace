<?php

namespace ploanframework\actions;

use ploanframework\constants\JdbErrors;
use rrxframework\base\JdbException;
use rrxframework\base\JdbLog;

/**
 * Class MqAction
 * @package ploanframework\actions
 * @author wangdj
 */
abstract class MqAction extends BaseAction{
    protected $check_inner = false;
    /** @var array mq topic */
    protected $topic = [];
    /** @var array mq tags */
    protected $tags = [];

    protected $flag = true;

    public function beforeExecute(){
        if(!isset($this->topic) || !isset($this->tags)){
            throw new JdbException(JdbErrors::ERR_NO_INNER_FAILED);
        }

        $topic = $this->get('topic');
        $tags = $this->get('tags');
        $resend = $this->get('resend',0);
        if(!in_array($topic, $this->topic)){
            $need = implode('|', $this->topic);
            throw new JdbException(JdbErrors::ERR_NO_PARAM_INVALID, null, "invalid topic[$topic] expect[{$need}]");
        }

        if(!in_array($tags, $this->tags)){
            $need = implode('|', $this->tags);
            throw new JdbException(JdbErrors::ERR_NO_PARAM_INVALID, null, "invalid tag[$tags] expect[{$need}]");
        }

        $body = $this->get('body');
        if(empty($body)){
            throw new JdbException(JdbErrors::ERR_NO_PARAM_INVALID, null, "body cannot be empty");
        }
        $this->params = json_decode($body, true);

        if(!$this->params){
            throw new JdbException(JdbErrors::ERR_NO_PARAM_INVALID, null, 'invalid body. json decode failed!');
        }
        $this->params['resend'] = $resend;
        $this->params['topic'] = $topic;
        $this->params['tag'] = $tags;
    }

    public function render($response = null){
        header("jdb_errno:{$this->returnCode}");
        if($this->flag){
            $this->response_data = ['flag' => 'success'];   //默认始终成功
        }else{
            $this->response_data = ['flag' => 'failed'];    //如果认为设置flag为false,则返回失败
        }

        JdbLog::notice('output[' . json_encode($this->response_data) . ']');
        echo json_encode($this->response_data);
        fastcgi_finish_request();
    }
}