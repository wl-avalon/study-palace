<?php
/**
 *author:deling
 *createTime :2017/8/15 下午7:29
 *descption:
 */

namespace ploanframework\services\shortUrl;


use ploanframework\apis\ApiContext;

class ShortUrlService
{
    /**
     * curl -d 'longUrl=http://www.baidu.com' http://100.73.16.59:4000/long2short
     *"shortUrl":"http://100.73.16.59:4001/6ywX4Eoi","longUrl":"http://www.baidu.com","cDate":1502795945659086135}
     *
     */
    public static function getShortUrl($longUrl)
    {
        $params = ['longUrl' => $longUrl];
        $ret = ApiContext::get('dwz', 'long2short', $params)->toArray();
        return $ret['shortUrl'];
    }
}

