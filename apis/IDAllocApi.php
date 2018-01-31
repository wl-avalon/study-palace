<?php
/**
 * Created by PhpStorm.
 * User: wzj-dev
 * Date: 18/1/31
 * Time: 下午6:02
 */
namespace app\apis;
use rrxframework\base\JdbLog;
use rrxframework\util\DictUtil;
use rrxframework\util\HttpUtil;
use Yii;

class IDAllocApi
{
    const INFO_OK = 0;
    const INFO_ERROR = 99999999;
    public static $arrCurlOption = array(
        CURLOPT_TIMEOUT_MS => 500,
        CURLOPT_CONNECTTIMEOUT_MS => 100,
    );

    //申请自增ID
    public static function nextId(){
        $arrPost = [];
        $arrTempOption = self::$arrCurlOption;
        $arrTempOption['retry'] = 1;

        $arrTempOption[CURLOPT_TIMEOUT_MS] = !empty(Yii::$app->params['idAlloc']['timeout_ms'])
            ? Yii::$app->params['idAlloc']['timeout_ms'] : $arrTempOption[CURLOPT_TIMEOUT_MS];
        $arrTempOption[CURLOPT_CONNECTTIMEOUT_MS] = !empty(Yii::$app->params['idAlloc']['conn_timeout_ms'])
            ? Yii::$app->params['idAlloc']['conn_timeout_ms'] : $arrTempOption[CURLOPT_CONNECTTIMEOUT_MS];
        $arrTempOption['retry'] = isset(Yii::$app->params['idAlloc']['retry']) ? Yii::$app->params['idAlloc']['retry'] : $arrTempOption['retry'];

        $arrPost['type'] = "";

        $req = [
            'url' => Yii::$app->params['idAlloc']['domain'] . Yii::$app->params['idAlloc']['apis']['nextId'],
            'option' => $arrTempOption,
            'get' => $arrPost,
        ];

        return self::commonIdgencurl($req, __FUNCTION__);
    }

    //批量申请自增IDs
    public static function batch($iCount){
        $arrPost = [];
        $arrTempOption = self::$arrCurlOption;
        $arrTempOption['retry'] = 1;

        $arrTempOption[CURLOPT_TIMEOUT_MS] = !empty(Yii::$app->params['idAlloc']['timeout_ms'])
            ? Yii::$app->params['idAlloc']['timeout_ms'] : $arrTempOption[CURLOPT_TIMEOUT_MS];
        $arrTempOption[CURLOPT_CONNECTTIMEOUT_MS] = !empty(Yii::$app->params['idAlloc']['conn_timeout_ms'])
            ? Yii::$app->params['idAlloc']['conn_timeout_ms'] : $arrTempOption[CURLOPT_CONNECTTIMEOUT_MS];
        $arrTempOption['retry'] = isset(Yii::$app->params['idAlloc']['retry']) ? Yii::$app->params['idAlloc']['retry'] : $arrTempOption['retry'];

        $arrPost['count'] = $iCount;

        $req = [
            'url' => Yii::$app->params['idAlloc']['domain'] . Yii::$app->params['idAlloc']['apis']['batch'],
            'option' => $arrTempOption,
            'get' => $arrPost,
        ];

        return self::commonBatchIdgencurl($req, __FUNCTION__);
    }

    public static function commonIdgencurl($req, $strInterface=""){
        if(!isset($req['option'])){
            $req['option'] = self::$arrCurlOption;
        }
        $fTimeStart = DictUtil::microtimeFloat();
        $data = HttpUtil::instance()->curl($req);
        $fTimeEnd = DictUtil::microtimeFloat();

        DictUtil::setTimeMap($strInterface, $fTimeEnd-$fTimeStart);

        if ($data['errno'] == self::INFO_OK) {
            if (empty($data['content'])) {
                JdbLog::warning("msg[$strInterface], file[" . __CLASS__ . ':' . __LINE__ . ']['.$data['msg'].']');
                return ['errno' => self::INFO_ERROR, 'msg' => '调用模块网络异常'];
            }
            $ret = json_decode($data['content'], true);
        }else{
            return ['errno' => $data['errno'], 'msg' => $data['msg']];
        }

        // 获取信息成功
        if (isset($ret['nextId']) && $ret['nextId'] != 0) {
            return ['errno' => self::INFO_OK, 'msg' => 'get info success', 'data' => $ret];
        }

        return ['errno' => $ret['error']['returnCode'], 'msg' => $ret['error']['returnUserMessage']];
    }

    public static function commonBatchIdgencurl($req, $strInterface=""){
        if(!isset($req['option'])){
            $req['option'] = self::$arrCurlOption;
        }
        $fTimeStart = DictUtil::microtimeFloat();
        $data = HttpUtil::instance()->curl($req);
        $fTimeEnd = DictUtil::microtimeFloat();

        DictUtil::setTimeMap($strInterface, $fTimeEnd-$fTimeStart);

        if ($data['errno'] == self::INFO_OK) {
            if (empty($data['content'])) {
                JdbLog::warning("msg[$strInterface], file[" . __CLASS__ . ':' . __LINE__ . ']['.$data['msg'].']');
                return ['errno' => self::INFO_ERROR, 'msg' => '调用模块网络异常'];
            }
            $ret = $data['content'];
        }else{
            return ['errno' => $data['errno'], 'msg' => $data['msg']];
        }

        // 获取信息成功
        if (!empty($ret)) {
            return ['errno' => self::INFO_OK, 'msg' => 'get info success', 'data' => $ret];
        }

        return ['errno' => $ret['error']['returnCode'], 'msg' => $ret['error']['returnUserMessage']];
    }
}