<?php

namespace ploanframework\services\notice;

use ploanframework\apis\ApiContext;
use ploanframework\constants\JdbErrors;
use rrxframework\base\JdbException;

class NoticeService{
    /** @var int 是否需要push， 1是 0否 */
    private $needPush = 0;
    /** @var int 是否需要功能模块数据推送， 1是 0否 */
    private $needBizPush = 0;
    /** @var int 是否需要飘新， 1是 0否 */
    private $needShowTips = 0;
    /** @var int 是否需要小秘书， 1是 0否 */
    private $needShowSecretary = 0;

    /** @var string 来源系统，在notice注册下发 */
    private $innerSource;
    private $targetApp = 1;

    /** @var int 商贷主app传98，马甲包借贷宝借钱981，马甲包飞豹贷极速版982，默认不传为借贷宝0 */
    private $targetAppId;

    /** @var PushInfo[] 推送数据集合 */
    private $pushList;
    /** @var TipsInfo[] */
    private $tipsList;
    /** @var BizInfo[] */
    private $bizInfoList;
    /** @var Secretary[] */
    private $secretaryList;

    private function getMessageList(){
        $users = array_keys($this->pushList) + array_keys($this->tipsList) + array_keys($this->bizInfoList) + array_keys($this->secretaryList);
        if(count($users) == 0){
            return [];
        }
        $messageAttr = [
            'needPush'          => $this->needPush,
            'needBizPush'       => $this->needBizPush,
            'needShowTips'      => $this->needShowTips,
            'needShowSecretary' => $this->needShowSecretary,
        ];
        $messages = [];
        foreach($users as $userId){
            $message = [
                'userId'      => strval($userId),
                'timestamp'   => time(),
                'targetApp'   => $this->targetApp,
                'innerSource' => $this->innerSource,
                'messageAttr' => $messageAttr,
            ];

            if(array_key_exists($userId, $this->pushList)){
                $message['pushInfo'] = $this->pushList[$userId]->toArray();
            }

            if(array_key_exists($userId, $this->tipsList)){
                $message['tipsInfo'] = $this->tipsList[$userId]->toArray();
            }

            if(array_key_exists($userId, $this->bizInfoList)){
                $message['bizInfo'] = $this->bizInfoList[$userId]->toArray();
            }

            if(array_key_exists($userId, $this->secretaryList)){
                $message['secretary'] = $this->secretaryList[$userId]->toArray();
            }

            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * NoticeService constructor.
     * @param string $innerSource 来源系统，在notice注册下发
     * @param int $targetAppId 商贷主app传98，马甲包借贷宝借钱981，马甲包飞豹贷极速版982，默认不传为借贷宝0
     */
    public function __construct($innerSource = 'ploan', $targetAppId = 0){
        $this->innerSource = $innerSource;
        $this->targetAppId = $targetAppId;
        $this->pushList = $this->tipsList = $this->bizInfoList = $this->secretaryList = [];
    }

    public function addPush($memberId, PushInfo $push){
        $this->pushList[$memberId] = $push;
    }

    public function addTips($memberId, TipsInfo $tipsInfo){
        $this->tipsList[$memberId] = $tipsInfo;
    }

    public function addBizInfo($memberId, BizInfo $bizInfo){
        $this->bizInfoList[$memberId] = $bizInfo;
    }

    public function addSecretary($memberId, Secretary $secretary){
        $this->secretaryList[$memberId] = $secretary;
    }

    public function send(){
        $messages = $this->getMessageList();
        if(count($messages) == 0){
            throw new JdbException(JdbErrors::ERR_NO_FUNCTION_CALL_FAIL, null, '发送通知失败', '参数错误，小秘书，push，弹窗，tips至少要一个');
        }

        if(count($messages) == 1){
            $message = $messages[0];
            return ApiContext::get('notice', 'addMessage', [
                'targetAppId' => $this->targetAppId, 'message' => json_encode($message)]);
        }else{
            return ApiContext::get('notice', 'batchAddMessage', [
                'targetAppId' => $this->targetAppId, 'messageList' => json_encode($messages)]);
        }
    }

    /**
     * @return int
     */
    public function getNeedPush(): int{
        return $this->needPush;
    }

    /**
     * @param int $needPush
     */
    public function setNeedPush(int $needPush){
        $this->needPush = $needPush;
    }

    /**
     * @return int
     */
    public function getNeedBizPush(): int{
        return $this->needBizPush;
    }

    /**
     * @param int $needBizPush
     */
    public function setNeedBizPush(int $needBizPush){
        $this->needBizPush = $needBizPush;
    }

    /**
     * @return int
     */
    public function getNeedShowTips(): int{
        return $this->needShowTips;
    }

    /**
     * @param int $needShowTips
     */
    public function setNeedShowTips(int $needShowTips){
        $this->needShowTips = $needShowTips;
    }

    /**
     * @return int
     */
    public function getNeedShowSecretary(): int{
        return $this->needShowSecretary;
    }

    /**
     * @param int $needShowSecretary
     */
    public function setNeedShowSecretary(int $needShowSecretary){
        $this->needShowSecretary = $needShowSecretary;
    }
}