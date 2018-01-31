<?php
namespace rrxframework\base;

use Yii;
use app\modules\components\Consts;
use rrxframework\util\RedisUtil;

class JdbMqCallbackAction extends JdbAction {

    const EXPIRE_TIME = 2592000; // 30 * 24 * 3600
    const MQ_INTERVAL_TIME = 1; // 两次回调的最小间隔时间

    const MAX_TIMEOUT = 10; // 默认消费者最长执行时间10s
    const CONSUME_UNKNOWN = 0;
    const CONSUME_SUCCESS = 1; // 消费成功后标识为成功

    protected $maxTimeout = 10; // 默认消费者最长执行时间,调用方可重写
    protected $redisName = 'codis'; // 调用方需要重写
    protected $checkDup = true; // 是否进行幂等性校验

    /*
     * $checkTopic 校验mq消费者接收的topic tag是否正确,配置形式为:
     * $checkTopic = [
     * "topic1" => [
     *     "tag1" => [],
     *     "tag2" => [],
     *  ],
     * "topic2" => [
     *      "tag3" => [],
     * ]
     * ];
     */
    protected $checkTopic = [];
    protected $check_login = false;

    private $executed = false; // 本次请求是否被执行
    private $time = ""; // 本次请求的时间
    private $logID = ""; // 本次请求的logID
    private $redisKey = ""; // 根据antMQ生产者发送消息时用于标记消息唯一的标识生成的key


    public function execute() {

    }

    public function checkParam() {
        $inputParams = $this->get();

        if(!empty($this->checkTopic)) {
            if(empty($inputParams['topic']) || empty($inputParams['tags'])) {
                JdbLog::warning("mq callback mis topic or tags: input " . json_encode($inputParams));
                throw new JdbException(self::INFO_OK, null, "success！", "success！");
            }

            $topic = trim($inputParams['topic']);
            $tags = trim($inputParams['tags']);
            if (!is_array($this->checkTopic[$topic]) ||
                !isset($this->checkTopic[$topic][$tags])) {
                JdbLog::warning("message error! input " . json_encode($inputParams));
                throw new JdbException(self::INFO_OK, null, "success！", "success！");
            }
        }

        $orderId = $this->get('orderId', '');
        $resend = $this->get('resend', '');
        
        JdbLog::debug('mq callback , orderId: ' . $orderId . ', resend:' . $resend);

        if(!empty($orderId)) {
            $orderId = trim($orderId);
            if(!empty($resend)){
                $orderId = $orderId . "-" . $resend;
            }

            $this->redisKey = $this->getRedisKey($orderId);
        } else {
            $this->checkDup = false; // 缺少orderId,不进行幂等性校验
        }

        if($this->checkDup) {
            $this->logID = JdbLog::getLogID();
            $this->time = time();

            $redis = RedisUtil::getInstance($this->redisName);
            $getLockRet = $redis->setnx($this->redisKey, $this->getRedisVal(self::CONSUME_UNKNOWN));
            if($getLockRet == false) { // 抢锁失败
                $redisRetStr = $redis->get($this->redisKey);
                if($redisRetStr === false) {
                    JdbLog::warning('connect redis failed!');
                    throw new JdbException(self::INFO_ERROR, null, "failed！", "failed！");
                }

                $lockVal = $this->explodeRedisVal($redisRetStr);
                if($lockVal['status'] == self::CONSUME_SUCCESS) {
                    // 已经处理过且处理成功了
                    JdbLog::debug('has already called successfully, orderId: ' . $orderId);
                    throw new JdbException(self::INFO_OK, null, "success！", "success！");
                }

                if($this->time - $lockVal['time'] <= $this->maxTimeout) {
                    // 上次消费可能超时未处理完 or 并发抢锁失败
                    JdbLog::debug('get lock failed! orderId: ' . $orderId);
                    throw new JdbException(self::INFO_ERROR, null, "lock failed!", "lock failed!");
                }

                // 之前消费超时且未消费成功,重新消费,再次防止并发
                $oldVal = $redis->getSet($this->redisKey, $this->getRedisVal(self::CONSUME_UNKNOWN));
                if($oldVal != $redisRetStr) {
                    // 抢锁失败, 返回成功
                    JdbLog::debug('not first call and get lock failed! orderId: ' . $orderId);
                    throw new JdbException(self::INFO_OK, null, "success！", "success！");
                }
            }
            $this->executed = true;
        }
    }

    public function afterExcute($response){
        if($this->checkDup && $this->executed) {

            $redis = RedisUtil::getInstance($this->redisName);
            if($this->response_data['flag'] == "fail") {
                // MQ 消费失败，释放锁
                $redis->del($this->redisKey);
            } else {
                // MQ 消费成功，标记该条消息消费成功
                $redis->setex($this->redisKey, self::EXPIRE_TIME, $this->getRedisVal(self::CONSUME_SUCCESS));
            }
        }
    }

    function exception($e) {
        $errno = $e->getCode();
        $errmsg = $e->getMessage();

        JdbLog::addNotice("ao_errno", $errno);
        JdbLog::addNotice("ao_errmsg", $errmsg);

        $this->mqRet($errno, $errmsg, null);
    }

    public function mqRet($errno, $usrmsg = null, $sysmsg = null) {
        $this->setResponseData($errno, null, $usrmsg, $sysmsg);
        return true;
    }

    public function setResponseData($errno, $err_info = null, $usrmsg = null, $sysmsg = null, $pop_data = null){
        JdbLog::setErrno($errno);
        $response = array(
            'flag' => 'success',
        );
        if(0 != $errno) {
            $response = array(
                'flag' => 'fail',
                'error' => array(
                    'returnCode'        => $errno,
                    'returnMessage'     => $sysmsg === null ? Consts::SYSTEM_ERROR : $sysmsg,
                    'returnUserMessage' => $usrmsg === null ? Consts::SYSTEM_ERROR : $usrmsg,
                ),
            );
        }
        header("jdb_errno:$errno");
        $this->response_data = $response;
    }

    protected function explodeRedisVal($redisRetStr) {
        $redisRetArr = explode('-', $redisRetStr);
        $lockVal = [
            'time' => $redisRetArr[0],
            'logID' => $redisRetArr[1],
            'status' => intval($redisRetArr[2]),
        ];

        return $lockVal;
    }

    // value 格式 当前时间戳-本次请求logID-处理结果
    protected function getRedisVal($status) {
        return  $this->time . '-' . $this->logID . '-' . $status;
    }

    protected function getRedisKey($orderId) {
        $moduleName = Yii::$app->id;
        $actionName = $this->getCalledName();

        return $moduleName . "_" . $actionName . "_" . $orderId;
    }

    protected static function getCalledName() {
        $calledClass = get_called_class();
        $arr = explode('\\', $calledClass);
        $len = count($arr);
        $actionName = substr($arr[$len-1], 0, -6);
        if(empty($actionName)) {
            JdbLog::warning('failed to get called action name!');
            throw new JdbException(self::INFO_ERROR, null, "failed！", "failed！");
        }

        return $actionName;
    }
    
}
