<?php
namespace rrxframework\util\tcc\api;
use Yii;
use rrxframework\base\JdbLog;
use rrxframework\util\HttpUtil;
use rrxframework\util\DictUtil;

class IdAllocApi {
    public static $type = "transcation";

    public static $arrCurlOption = array(
        CURLOPT_TIMEOUT_MS => 200,
        CURLOPT_CONNECTTIMEOUT_MS => 50,
        'retry'         => 1,
    );
    
    //http://119.254.102.187:5021/nextId
    public static function getAllocId(){
      $arrPostData['type'] = self::$type;
      $req = [
          'url' => Yii::$app->params['idAlloc']['domain'] . Yii::$app->params['idAlloc']['api']['nextId'], 
          'post' => $arrPostData,
          'option' => self::$arrCurlOption,
      ];

      return self::commoncurl($req, __FUNCTION__);
    }
  
    public static function commoncurl($req, $strInterface=""){
        if(!isset($req['option'])){
            $req['option'] = self::$arrCurlOption;
        }
        $fTimeStart = DictUtil::microtimeFloat();
        $data = HttpUtil::instance()->curl($req);
        $fTimeEnd = DictUtil::microtimeFloat();
        
        DictUtil::setTimeMap($strInterface, $fTimeEnd-$fTimeStart);

        $ret = false;
        if ($data['errno'] == 0) {
            if (empty($data['content'])) {
                JdbLog::warning("msg[$strInterface], file[" . __CLASS__ . ':' . __LINE__ . ']['.$data['msg'].']');
                return ['errno' => 1, 'msg' => '用户中心网络异常'];
            }
            $ret = json_decode($data['content'], true);
        }
        // 获取信息成功
        if (isset($ret['nextId']) && !empty($ret['nextId'])) {
            return ['errno' => 0, 'msg' => 'get user info succ',
                    'data' => $ret['nextId']];
        }

        JdbLog::warning("msg[common_ret_error], req[".serialize($req)."], ret[".serialize($data)."], file[" . __CLASS__ . ':' . __LINE__ . ']');
        
        return ['errno' => 2, 'msg' => isset($ret['error']) ? $ret['error']: "fail to alloc id"];
    }
}

?>