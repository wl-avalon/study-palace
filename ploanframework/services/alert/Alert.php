<?php

namespace ploanframework\services\alert;

use ploanframework\apis\IdAllocApi;
use ploanframework\components\AlertException;
use ploanframework\constants\JdbErrors;
use rrxframework\util\RedisUtil;

/**
 * Created by PhpStorm.
 * User: AndreasWang
 * Date: 2018/1/19
 * Time: 下午2:11
 */
class Alert{
    private $title;
    private $content;
    private $isBlock;
    private $type;
    private $btnList;
    private $receiptId;

    public function __construct($title, $content, $type, $isBlock = 0, $receiptId = null){
        $this->title = $title;
        $this->content = $content;
        $this->type = $type;
        $this->isBlock = $isBlock;
        $this->btnList = [];
        $this->receiptId = $receiptId;
    }

    public function pop(){
        /** @var \Redis $redis */
        $redis = RedisUtil::getInstance('redis');
        $receiptInfo = \Yii::$app->request->post('routerReceiptInfo');
        $receiptId = json_decode($receiptInfo, true)['actionReceiptID']??'';
        if(empty($receiptId) || empty($redis->get('ploan_common_alter_' . $receiptId))){
            $popUpRouter = [
                'data' => json_encode([
                    'title'   => $this->title??'',
                    'content' => $this->content??'',
                    'isBlock' => $this->isBlock,
                    'btnList' => $this->btnList,
                ]),
                'type' => $this->type,
            ];
            $receiptId = $this->getReceiptId();
            $redis->set('ploan_common_alter_' . $receiptId, 1);
            $redis->expire('ploan_common_alter_' . $receiptId, 60);
            throw new AlertException(JdbErrors::ERR_NO_SUCCESS, $popUpRouter);
        }
        $redis->del('ploan_common_alter_' . $receiptId, 1);
    }

    public function getReceiptId(){
        if($this->receiptId == null){
            $this->receiptId = IdAllocApi::nextId();
        }
        return $this->receiptId;
    }

    public function addButton($text, $color = null, $isReceipt = 0, $actionType = 0, $actionUrl = '', $stateKey = null, $actionReceiptExt = null){
        $this->btnList[] = [
            // 按钮名称
            'text'             => $text,
            'color'            => $color,
            // 1:需要回执；0：不需要
            'isReceipt'        => strval($isReceipt),
            //埋点设置
            'statKey'          => $stateKey,
            // 回执ID
            'actionReceiptID'  => $isReceipt == 0 ? null : $this->getReceiptId(),
            // 操作类型区分(0：无动作，如：取消、不同意等；1：继续、确认；2：跳转)
            'actionType'       => strval($actionType),
            // 如果actionType=2的情况，下发跳转的url
            'actionUrl'        => $actionUrl,
            // 回传用户的参数
            'actionReceiptExt' => $actionReceiptExt,
        ];
        return $this;
    }
}