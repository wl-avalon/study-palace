<?php
return [
    'session'  => [
        'apis' => [
            'checkVisitorLogin' => '/mybankv21/gosession/checkVisitorLogin',
        ],
    ],
    'auth'     => [
        'serverType' => 1,
        'apis'       => [
            'register'    => '/mybankv21/auc/authorize/register',
            'checkResult' => '/mybankv21/auc/authorize/check',
        ],
    ],
    'passport' => [
        'serverType' => 1,
        'apis'       => [
            'getUserList'         => '/phppassport/passport/inner/getulist',
            'getUserInfo'         => '/phppassport/passport/inner/getuinfo',
            'getUserListByPhone'  => '/phppassport/passport/inner/getulistbyphonenum',
            'getUserInfoByPhone'  => '/phppassport/passport/inner/getuinfobyphonenum',
            'setStatusBit'        => '/phppassport/v2/passport/inner/setStatusBit',
            'checkWebAccessToken' => '/phppassport/v2/passport/inner/checkWebAccessToken',
            'checkpctoken'        => '/phppassport/v2/passport/inner/checkpctoken',
        ],
    ],
    'notice'   => [
        'apis' => [
            'addMessage'      => '/notice/inner/message/addMessage',
            'batchAddMessage' => '/notice/inner/message/batchAddMessage',
        ],
    ],
    'sms'      => [
        'apis' => [
            'send' => '/sms/smsController/sendSms',
        ],
    ],
    /**
     * http://100.73.16.59:4000/long2short
     *http://100.111.222.84/mybankv21/shorturl/long2short
     */
    'dwz'      => [ //短网址服务
        'apis' => [
            'long2short' => '/long2short',
        ],
    ],
    'risk'     => [
        'apis' => [
            'windTips' => '/mybankv21/aeolus/wind/tips',
        ],
    ],
    'idAlloc'  => [
        'apis' => [
            'nextId' => '/mybankv21/idgen/nextId',      //取一个ID
            'batch'  => '/mybankv21/idgen/batch',       //取多个ID
        ],
    ],
];
