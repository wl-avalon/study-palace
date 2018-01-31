<?php
namespace rrxframework\service;
use rrxframework\base\JdbLog;
use rrxframework\base\JdbService;
use rrxframework\util\ServiceWfUtil;
use Yii;
use yii\web\Response;

class MQ {
    const SERVICE_NAME = 'mq';

    const PUT_MSG_URL = '/mqagent/mqagent/putmsg';

    const SUCCESS_RETURN_CODE = 'SEND_SUCCESS';

    /**
     * 推送消息
     *
     * 会对推送消息进行格式和returnCode校验,异常的话记录log
     *
     * @param string $appName 应用名称,appName topic tag联系基础架构部负责mq的同学申请添加
     * @param string $topic 主题
     * @param string $tag 子节点
     * @param string $orderId 订单ID 确定是业务逻辑上的唯一id
     * @param mix $body 推送到下游的主题消息
     * @param string $serviceName 服务名,即server.ini中mq对应的section
     * @return bool
     */
    static public function pushMsg($appName, $topic, $tag, $orderId, $body
        ,$serviceName = self::SERVICE_NAME, $orderKey = null) {
        $curlInput = [
            'appname' => $appName,
            'topic' => $topic,
            'tag' => $tag,
            'orderId' => $orderId,
            'body' => is_array($body) ? json_encode($body) : $body,
        ];

        if(!empty($orderKey)){
            $curlInput['orderKey'] = $orderKey;
        }
        
        $result = JdbService::call($serviceName, self::PUT_MSG_URL, $curlInput
            , JdbService::SERVICE_REQUEST_MODE_HTTP_POST);

        if ($result === false) {
            return false;
        }

        $field = '';
        if (!isset($result['messageId'])) {
            $field = 'messageId';
        } else if (!isset($result['resultCode'])) {
            $field = 'resultCode';
        }

        if (!empty($field)) {
            ServiceWfUtil::formatLog($serviceName, ServiceWfUtil::FORMAT_ERROR_TYPE_LACK_FIELD, $field
                , self::PUT_MSG_URL, $curlInput, $result);

            return false;
        }

        if ($result['resultCode'] != self::SUCCESS_RETURN_CODE) {
            ServiceWfUtil::errnoLog($serviceName, $result['resultCode'], '', self::PUT_MSG_URL
                , $curlInput, $result);

            return false;
        }

        return true;
    }

    /**
     * 格式化回调参数
     * 格式化后的topic tags body通过函数入参引用赋值
     * 格式化错误时打印wf log
     *
     * @param array $param 回调入参
     * @param string $topic
     * @param string $tags
     * @param array $body
     * @param string $expectTopic 期望的topic
     * @param string $expectTags 期望的tags
     * @return bool
     */
    static public function formatCallbackParam(array $param, &$topic, &$tags, array &$body
        , $expectTopic, $expectTags) {
        if (empty($param['topic']) || empty($param['tags']) || empty($param['body'])) {
            Yii::warning("callback_error:param_empty input[".json_encode($param) . "]");

            return false;
        }
        $topic = trim($param['topic']);
        $tags = trim($param['tags']);
        $bodyStr = trim($param['body']);

        if (($topic != $expectTopic) || ($tags != $expectTags)) {
            Yii::warning("callback_error:no_expect_value input[".json_encode($param) . "]");

            return false;
        }

        $body = json_encode($bodyStr, true);
        if (!$body) {
            Yii::warning("callback_error:body_json_error input[".json_encode($param) . "]");

            return false;
        }

        return true;
    }

    /**
     * @return array
     */
    static public function getSuccessResponseData() {
        return [
            'flag' => 'success',
        ];
    }

    /**
     * @param $errno
     * @param $errmsg
     * @param $usermsg
     * @return array
     */
    static public function getFailResponseData($errno, $errmsg, $usermsg) {
        return [
            'flag' => 'fail',
            'error' => [
                'returnCode'        => $errno,
                'returnMessage'     => $errmsg,
                'returnUserMessage' => $usermsg,
            ],
        ];
    }

    /**
     * @param $errno
     * @param $errmsg
     * @param $usermsg
     * @return array
     */
    static public function display($errno, $errmsg, $usermsg) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (0 === $errno) {
            $response = self::getSuccessResponseData();
        } else {
            $response =self::getFailResponseData($errno, $errmsg, $usermsg);
        }
        $headers = Yii::$app->response->headers;
        $headers->add('Content-Type', 'application/json');

        return $response;
    }
}