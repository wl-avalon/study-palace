<?php

namespace ploanframework\services\passport;

use ploanframework\apis\ApiContext;
use ploanframework\apis\models\Response;
use ploanframework\constants\JdbConsts;

class PassportService{
    /**
     * 通过用户id批量获取用户信息
     * @param array $uuids 用户 id 列表
     * @param string $fields
     *
     * @return array 用户信息列表
     * @author wangdj
     */
    public static function getUsersByUuids($uuids, $fields = 'base'){
        $result = [];
        if(empty($uuids) || count($uuids) <= 0){
            return $result;
        }

        $collection = [];
        for($i = 0; $i <= count($uuids); $i += 200){
            $params = [
                'user_id_list' => join(',', array_slice($uuids, $i, 200)),
                'result_type'  => 2,
                'fields'       => $fields,
            ];
            $collection[] = ApiContext::get('passport', 'getUserList', $params)->throwWhenFailed();
        }

        foreach($collection as $resp){
            $result += $resp->toArray();
        }

        return $result;
    }

    /**
     * 通过用户id获取用户信息
     * 返回参数错误检查
     * @param  string $userId [用户id]
     * @param string $fields
     * @param int $screenWidth
     *
     * @return Response
     */
    public static function getUserByUuid($userId, $fields = 'ext', $screenWidth = 750){
        $ret = ApiContext::get('passport', 'getUserInfo', [
            'user_id' => $userId,
            'fields'  => $fields,
            'screen_width' => $screenWidth,
        ])
            ->throwWhenFailed();

        if (empty($ret['avatar_url'])) {
            $ret['avatar_url'] = JdbConsts::DEFAULT_AVATAR_URL;
        }
        return $ret;
    }

    /**
     * 通过手机号码批量获取用户信息
     * @param array $phones
     * @param string $fields
     * @return array
     */
    public static function getUsersByPhones($phones, $fields = 'base'){
        $result = [];
        if(empty($phones) || count($phones) <= 0){
            return $result;
        }

        $collection = [];
        for($i = 0; $i <= count($phones); $i += 200){
            $params = [
                'phone_num_list' => join(',', array_slice($phones, $i, 200)),
                'fields'         => $fields,
                'result_type'    => 2,
            ];
            $collection[] = ApiContext::get('passport', 'getUserListByPhone', $params)->throwWhenFailed();
        }
        foreach($collection as $resp){
            $result += $resp->toArray();
        }

        return $result;
    }

    /**
     * 通过手机号码查询用户信息
     * @param $phone
     * @return Response
     */
    public static function getUserByPhone($phone){
        return ApiContext::get('passport', 'getUserInfoByPhone', [
            'phone_num' => $phone,
        ])
            ->throwWhenFailed();
    }

    public static function getUserByUuidNoException($userId, $fields = 'ext'){
        return ApiContext::get('passport', 'getUserInfo', [
            'user_id' => $userId,
            'fields'  => $fields,
        ]);
    }

    /**
     * 端外验证是否登录
     * @param $accessToken
     * @return Response
     */
    public static function checkWebAccessToken($accessToken)
    {
        return ApiContext::post('passport', 'checkWebAccessToken', [
            'access_token' => $accessToken,
        ]);
    }

    public static function checkpctoken($token){
        return ApiContext::get('passport', 'checkpctoken', [
            'login_token' => $token,
        ])->throwWhenFailed()->toArray();
    }
}
