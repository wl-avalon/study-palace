<?php

namespace ploanframework\services\notice;

class PushInfoType{
    /** 交易推送信息, 线下审核??? */
    const TRADE_OFFLINE = 1;
    /** 好友推送信息 */
    const FRIEND = 2;
    /** 订阅推送信息 */
    const SUBSCRIBE = 3;
    /**系统消息 */
    const SYSTEM = 6;
    /**交易消息 */
    const TRADE = 7;
    /**催收消息 */
    const COLLECTION = 8;
    /**在线客服消息 */
    const CUSTOMER_SERVICE = 9;
}