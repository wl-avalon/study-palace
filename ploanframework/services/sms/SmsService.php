<?php

namespace ploanframework\services\sms;

use ploanframework\apis\ApiContext;
use ploanframework\apis\models\Response;
use ploanframework\constants\JdbErrors;
use rrxframework\base\JdbException;

class SmsService{
    /**
     * @param string $templateId 短信模板号
     * @param array $mobiles 手机号
     * @param int $msgType 信息类型，1-短信，2-彩信
     * @param array $smsData 根据模板中定义的参数
     * @param string $sendTime 短信定时发送时间(yyyyMMddHHmmss)，不需要定时发送请传空
     * @param string $businessId 业务id，需要通过接口查询短信送达状态时必填，和businessType唯一确定一条发送记录
     * @param string $businessType 业务类型，需要通过接口查询短信送达状态时必填，和businessId唯一确定一条发送记录
     *
     * @throws JdbException
     * @return Response
     */
    public static function send($templateId, array $mobiles, $smsData, $businessId = null, $businessType = null, $msgType = 1, $sendTime = null){
        $params = [
            'mobiles'    => implode(',', $mobiles),
            'templateId' => $templateId,
            'msgType'    => $msgType,
            'sendTime'   => $sendTime??'',
        ];
        $params = array_merge($params, $smsData);
        if(empty($businessId) xor empty($businessType)){
            throw new JdbException(JdbErrors::ERR_NO_PARAM_INVALID);
        }
        if(!empty($businessId)){
            $params['businessId'] = $businessId;
            $params['businessType'] = $businessType;
        }
        return ApiContext::get('sms', 'send', $params);
    }
}