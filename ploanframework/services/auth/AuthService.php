<?php

namespace ploanframework\services\auth;

use ploanframework\apis\ApiContext;
use ploanframework\components\AuthException;
use ploanframework\constants\JdbErrors;
use ploanframework\constants\RedisKey;
use rrxframework\base\JdbException;
use rrxframework\util\IpUtil;
use rrxframework\util\RedisUtil;

/**
 * Class AuthService
 * @package app\modules\datashop\services\auth
 * @author wangdj
 * @since 2017-03-06
 */
class AuthService{

    /**
     * 通用鉴权处理方法
     *
     * @param string $authType
     * @param array $params
     * @throws JdbException|AuthException
     * @return array|mixed
     */
    public static function checkAuth($authType, array $params){
        if(!isset($params['eventID'])){
            return self::register($params, $authType);
        }else{
            return self::checkResult($params, $authType);
        }
    }

    /**
     * @param array $data
     * @param $type
     * @return array
     * @throws AuthException
     * @throws JdbException
     */
    private static function register(array $data, $type){
        $data['ip'] = IpUtil::getClientIp();
        $params = [
            'data' => json_encode($data),
            'auc'  => json_encode(['type' => $type, 'to' => '', 'amount' => '', 'payType' => '', 'cardID' => '', 'orderID' => '', 'ext' => '',]),
        ];

        $result = ApiContext::get('auth', 'register', $params)->ignore(451);
        $returnCode = $result->getReturnCode();
        if($returnCode == 451){
            $redis = RedisUtil::getInstance('redis');
            $key = RedisKey::AUTH_PARAMS_PREFIX . '_' . $type . '_' . $result['auc']['eventID'];
            $redis->set($key, $params['data']);
            $redis->expire($key, 3600);
            throw new AuthException($returnCode, $result['data'], $result['auc'], '', $result->getReturnMessage()); //鉴权451不能返回returnUserMessage，踩坑
        }elseif($returnCode == 0){
            return $data;
        }

        throw new JdbException($returnCode, null, $result->getReturnUserMessage());
    }

    /**
     * @param array $data
     * @param int $type
     * @return array
     * @throws JdbException
     */
    private static function checkResult(array $data, $type){
        $params = ['data' => json_encode(['memberID' => $data['memberID'], 'eventID' => $data['eventID'], 'ts' => time(),]),];

        ApiContext::post('auth', 'checkResult', $params)->throwWhenFailed();

        $redis = RedisUtil::getInstance('redis');
        $key = RedisKey::AUTH_PARAMS_PREFIX . '_' . $type . '_' . $data['eventID'];
        $redisData = $redis->get($key);
        $redis->del($key);
        if(empty($redisData)){
            $redisData = $redis->get(RedisKey::Auth_PARAMS_PREFIX_OLD . '_' . $type . '_' . $data['eventID']);
        }
        if(!empty($redisData)){
            return json_decode($redisData, true);
        }else{
            throw new JdbException(JdbErrors::ERR_NO_UNKNOWN, null, '鉴权失败');
        }
    }
}