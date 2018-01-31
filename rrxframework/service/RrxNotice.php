<?php
namespace rrxframework\service;
use rrxframework\util\ServiceWfUtil;
use Yii;

class RrxNotice extends Base {
    static protected $serviceName = 'rrx_notice';

    // 发送飘新
    const PUSH_NEWS_URL = '/mybankv21/notice/inner/tips/pushTipsList';

    /**
     * 飘新
     *
     * @param $userIdToBizIdMap
     * @param $noticeType
     * @return bool
     */
    static public function pushNews($userIdToBizIdMap, $noticeType) {
        $newList = [];

        foreach ($userIdToBizIdMap as $userId => $bizId) {
            $newList[] = [
                'userId' => strval($userId),
                'bizId' => strval($bizId),
                'type' => $noticeType,
            ];
        }

        $param = [
            'newsInfo' => json_encode($newList),
        ];

        $status = self::call(self::PUSH_NEWS_URL, $param, $result);

        if (!$status) {
            return false;
        }

        if ($result['error']['returnCode'] != 0) {
            ServiceWfUtil::errnoLog(static::$serviceName, $result['error']['returnCode']
                , $result['error']['returnMessage'], self::PUSH_NEWS_URL, $param, json_encode($result));
            return false;
        }

        return true;
    }

    static protected function checkFormat($result, $urlPath, $formatParam) {
        $lackField = '';

        if (!isset($result['error'])) {
            $lackField = 'error';
        } else if (!isset($result['error']['returnCode'])) {
            $lackField = 'error.returnCode';
        }

        if (!empty($lackField)) {
            ServiceWfUtil::formatLog(self::$serviceName, ServiceWfUtil::FORMAT_ERROR_TYPE_LACK_FIELD, $lackField
                , $urlPath, $formatParam, json_encode($result));

            return false;
        }

        return true;
    }
}