<?php

namespace ploanframework\constants;

class JdbErrors{
    const FIELD_ERR_MSG = 'err_msg';
    const FIELD_USER_MSG = 'user_msg';

    const ERR_NO_SUCCESS = 0;

    const ERR_NO_SERVER_BUSY = 100000;

    const ERR_NO_UNKNOWN = 100001;

    const ERR_NO_PDO_EXCEPTION = 100100;

    const ERR_NO_OPERATION_TOO_FREQUENTLY = 100101;

    const ERR_NO_OPERATION_FAIL = 100102;

    const ERR_NO_DATA_PARSE_FAIL = 100103;

    const ERR_NO_FUNCTION_NOT_EXISTS = 100104;

    const ERR_NO_FUNCTION_CALL_FAIL = 100105;

    const ERR_NO_TOKEN_EXPIRED = 100106;

    const ERR_NO_PARAM_INVALID = 200000;

    const ERR_NO_PHONE_NUM_INVALID = 200001;

    const ERR_NO_VER_CODE_INVALID = 200002;

    const ERR_NO_LOGIN_FAILED = 300000;

    const ERR_NO_SECERT_ERROR = 300001;

    const ERR_NO_VER_FAILED = 300002;

    const ERR_NO_INNER_FAILED = 300003;

    const ERR_NO_INPUT_INFO_INVALID = 300004;

    const ERR_NO_RESULT_NOT_FOUND = 400000;

    const ERR_NO_RESULT_INVALID = 400001;

    const ERR_NO_RECENT_UP_PHONE = 500000;

    const ERR_NO_PHONE_HAS_OCCUPIED = 500001;

    const ERR_NO_PHONE_CHANGE_NOT_ALLOWED_BY_DITUI = 500002;

    const ERR_NO_IDENT_HAS_OCCUPIED = 501000;

    const ERR_NO_IDENT_SMSCODE_TOO_FREQUENCY = 501001;

    const ERR_NO_NO_SAFE_URL = 502000;

    const ERR_NO_INVALID_UNLOCKED_USER = 503000;

    const ERR_NO_USER_NOT_EXIST = 503001;

    const ERR_NO_PASSWORD_ERROR = 503002;

    const ERR_NO_TOKEN_ERROR = 503100;

    const ERR_NO_INVALID_USER_VALIDATEFLAS = 503101;

    const ERR_NO_USER_IS_BLOCKED = 503102;

    const ERR_NO_USER_NEED_AUC = 503103;

    const ERR_NO_USER_IS_LOSS = 503104;

    const ERR_NO_USER_IS_CANNEL = 503105;

    const ERR_NO_FORBIDDEN_LOGIN = 503106;

    const ERR_NO_USER_EXIST = 503107;

    const ERR_NO_NEED_THIRD_AUTH = 503108;

    const ERR_NO_NEED_THIRD_BIND = 503109;

    const ERR_NO_USER_HAS_SIGNED = 506000;

    const ERR_NO_USER_HAS_NO_REAL_NAME = 504005;

    const ERR_NO_PAYUI_RESULT = 518001;

    const ERR_NO_USER_HAS_EXITS = 20210;

    const ERR_NO_CAN_NOT_REGISTER = 20215;

    static public $error_map = [
        self::ERR_NO_SUCCESS                           => [
            self::FIELD_ERR_MSG  => 'success',
            self::FIELD_USER_MSG => '操作成功',
        ],
        self::ERR_NO_SERVER_BUSY                       => [
            self::FIELD_ERR_MSG  => 'server_busy',
            self::FIELD_USER_MSG => '服务器繁忙，请稍后重试。',
        ],
        self::ERR_NO_DATA_PARSE_FAIL                   => [
            self::FIELD_ERR_MSG  => 'data_parse_fail',
            self::FIELD_USER_MSG => '数据解析失败',
        ],
        self::ERR_NO_FUNCTION_NOT_EXISTS               => [
            self::FIELD_ERR_MSG  => 'function_not_exists',
            self::FIELD_USER_MSG => '调用函数不存在',
        ],
        self::ERR_NO_FUNCTION_CALL_FAIL                => [
            self::FIELD_ERR_MSG  => 'function_call_fail',
            self::FIELD_USER_MSG => '函数调用失败',
        ],
        self::ERR_NO_TOKEN_EXPIRED                     => [
            self::FIELD_ERR_MSG  => 'token_expired',
            self::FIELD_USER_MSG => 'token已过期',
        ],
        self::ERR_NO_UNKNOWN                           => [
            self::FIELD_ERR_MSG  => 'unknow',
            self::FIELD_USER_MSG => '未知异常',
        ],
        self::ERR_NO_PDO_EXCEPTION                     => [
            self::FIELD_ERR_MSG  => 'pdo_exception',
            self::FIELD_USER_MSG => 'PDO异常',
        ],
        self::ERR_NO_LOGIN_FAILED                      => [
            self::FIELD_ERR_MSG  => 'login failed',
            self::FIELD_USER_MSG => '登陆错误',
        ],
        self::ERR_NO_SECERT_ERROR                      => [
            self::FIELD_ERR_MSG  => 'secret error',
            self::FIELD_USER_MSG => '密钥错误或已经过期，请重新获取。',
        ],
        self::ERR_NO_PASSWORD_ERROR                    => [
            self::FIELD_ERR_MSG  => 'password error',
            self::FIELD_USER_MSG => '密码错误，请重试！',
        ],
        self::ERR_NO_USER_HAS_NO_REAL_NAME             => [
            self::FIELD_ERR_MSG  => "user_has_no_real_name",
            self::FIELD_USER_MSG => "账户未通过实名认证",
        ],
        self::ERR_NO_TOKEN_ERROR                       => [
            self::FIELD_ERR_MSG  => 'token error',
            self::FIELD_USER_MSG => 'token无效',
        ],
        self::ERR_NO_OPERATION_TOO_FREQUENTLY          => [
            self::FIELD_ERR_MSG  => 'operation_too_frequently',
            self::FIELD_USER_MSG => '操作太过频繁，请稍后再试',
        ],
        self::ERR_NO_OPERATION_FAIL                    => [
            self::FIELD_ERR_MSG  => 'operation_fail',
            self::FIELD_USER_MSG => '操作失败',
        ],
        self::ERR_NO_PARAM_INVALID                     => [
            self::FIELD_ERR_MSG  => 'param_invalid',
            self::FIELD_USER_MSG => '所填信息有误，请填写正确的信息后重新提交',
        ],
        self::ERR_NO_PHONE_NUM_INVALID                 => [
            self::FIELD_ERR_MSG  => 'phone_num_invalid',
            self::FIELD_USER_MSG => '请输入真实的手机号',
        ],
        self::ERR_NO_VER_CODE_INVALID                  => [
            self::FIELD_ERR_MSG  => 'ver_code_invalid',
            self::FIELD_USER_MSG => '验证码错误，请填写正确的验证码后重新提交',
        ],
        self::ERR_NO_VER_FAILED                        => [
            self::FIELD_ERR_MSG  => 'ver_failed',
            self::FIELD_USER_MSG => '认证失败',
        ],
        self::ERR_NO_INNER_FAILED                      => [
            self::FIELD_ERR_MSG  => 'inner_failed',
            self::FIELD_USER_MSG => '内网认证失败',
        ],
        self::ERR_NO_RESULT_NOT_FOUND                  => [
            self::FIELD_ERR_MSG  => 'result_not_found',
            self::FIELD_USER_MSG => '未检索到相关记录',
        ],
        self::ERR_NO_RESULT_INVALID                    => [
            self::FIELD_ERR_MSG  => 'result_invalid',
            self::FIELD_USER_MSG => '结果不合法',
        ],
        self::ERR_NO_RECENT_UP_PHONE                   => [
            self::FIELD_ERR_MSG  => 'recent_up_phone',
            self::FIELD_USER_MSG => "您近期更换过手机号，请勿频繁更换",
        ],
        self::ERR_NO_PHONE_HAS_OCCUPIED                => [
            self::FIELD_ERR_MSG  => 'phone_has_occupied',
            self::FIELD_USER_MSG => "该手机号已被注册，请使用未注册手机号进行更换",
        ],
        self::ERR_NO_PHONE_CHANGE_NOT_ALLOWED_BY_DITUI => [
            self::FIELD_ERR_MSG  => 'phone_change_not_allowed_by_ditui',
            self::FIELD_USER_MSG => "无法更换至该手机号（该手机号有拉新历史），如有疑问，请联系借贷宝客服4001006699处理",
        ],
        self::ERR_NO_IDENT_HAS_OCCUPIED                => [
            self::FIELD_ERR_MSG  => 'ident_has_occupied',
            self::FIELD_USER_MSG => "该身份信息已被认证，如有疑问，请联系借贷宝客服4001006699处理",
        ],
        self::ERR_NO_IDENT_SMSCODE_TOO_FREQUENCY       => [
            self::FIELD_ERR_MSG  => 'smscode_too_frequency',
            self::FIELD_USER_MSG => "验证码发送过于频繁，请稍候再次尝试",//TODO:待pm给文案
        ],
        self::ERR_NO_INPUT_INFO_INVALID                => [
            self::FIELD_ERR_MSG  => 'input_info_invalid',
            self::FIELD_USER_MSG => "所填信息有误，请填写正确的信息后重新提交",
        ],
        self::ERR_NO_NO_SAFE_URL                       => [
            self::FIELD_ERR_MSG  => 'no_safe_url',
            self::FIELD_USER_MSG => "不安全的链接",
        ],
        self::ERR_NO_INVALID_UNLOCKED_USER             => [
            self::FIELD_ERR_MSG  => "invalid_unlocked_user",
            self::FIELD_USER_MSG => "该帐号不是在用户中心被封禁,无法解封",
        ],
        self::ERR_NO_INVALID_USER_VALIDATEFLAS         => [
            self::FIELD_ERR_MSG  => "invalid_user_validateFlag",
            self::FIELD_USER_MSG => "该帐号的状态无效",
        ],
        self::ERR_NO_USER_NOT_EXIST                    => [
            self::FIELD_ERR_MSG  => "user_not_exist",
            self::FIELD_USER_MSG => "该帐号不存在",
        ],
        self::ERR_NO_USER_IS_BLOCKED                   => [
            self::FIELD_ERR_MSG  => "user_is_blocked",
            self::FIELD_USER_MSG => "该帐号被封禁",
        ],
        self::ERR_NO_USER_NEED_AUC                     => [
            self::FIELD_ERR_MSG  => "user_need_auc",
            self::FIELD_USER_MSG => "该账号需要完成鉴权操作",
        ],
        self::ERR_NO_USER_IS_LOSS                      => [
            self::FIELD_ERR_MSG  => "user_is_loss",
            self::FIELD_USER_MSG => "您的借贷宝账号已挂失，如需解除挂失,请拨打客服电话 400-100-6699！",
        ],
        self::ERR_NO_USER_IS_CANNEL                    => [
            self::FIELD_ERR_MSG  => "user_is_cannel",
            self::FIELD_USER_MSG => "该帐号已注销",
        ],
        self::ERR_NO_FORBIDDEN_LOGIN                   => [
            self::FIELD_ERR_MSG  => "forbidden_loggin",
            self::FIELD_USER_MSG => "该账号禁止登陆",
        ],
        self::ERR_NO_USER_EXIST                        => [
            self::FIELD_ERR_MSG  => "user_exist",
            self::FIELD_USER_MSG => "用户已经存在",
        ],
        self::ERR_NO_PAYUI_RESULT                      => [
            self::FIELD_ERR_MSG  => "no result",
            self::FIELD_USER_MSG => "没有支付结果",
        ],
        self::ERR_NO_USER_HAS_SIGNED                   => [
            self::FIELD_ERR_MSG  => "user_has_signed",
            self::FIELD_USER_MSG => "用户已签到",
        ],
        self::ERR_NO_NEED_THIRD_AUTH                   => [
            self::FIELD_ERR_MSG  => "need_third_auth",
            self::FIELD_USER_MSG => "第三方账户未进行授权",
        ],
        self::ERR_NO_NEED_THIRD_BIND                   => [
            self::FIELD_ERR_MSG  => "need_third_bind",
            self::FIELD_USER_MSG => "第三方账号未绑定借贷宝账号",
        ],
        self::ERR_NO_USER_HAS_EXITS                    => [
            self::FIELD_ERR_MSG  => "alread_registed",
            self::FIELD_USER_MSG => "该号码已注册，是否登录？",
        ],
        self::ERR_NO_CAN_NOT_REGISTER                  => [
            self::FIELD_ERR_MSG  => "cannot_register",
            self::FIELD_USER_MSG => "该手机号曾被注册，现已解绑，三个月内无法使用。",
        ],
    ];

    static public function getUserMsg($errno){
        if(!isset(self::$error_map[$errno])){
            $errno = self::ERR_NO_UNKNOWN;
        }
        return self::$error_map[$errno][self::FIELD_USER_MSG];
    }

    static public function getErrMsg($errno){
        if(!isset(self::$error_map[$errno])){
            $errno = self::ERR_NO_UNKNOWN;
        }
        return self::$error_map[$errno][self::FIELD_ERR_MSG];
    }
}
