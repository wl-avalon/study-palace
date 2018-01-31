<?php
namespace rrxframework\base;
use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\base\InlineAction;

/**
 * 借贷宝控制器基类
 * 
 * @author hongcq@jiedaibao.com
 */
class JdbController extends Controller
{

    const INFO_OK = 0;
    const INFO_ERROR = 1;
    
    /**
     * 请求初始化
     * 
     * @see \yii\base\Object::init()
     */
    public function init() {
        parent::init();
    }

    
    
    /**
     * output json format information
     * 
     * @param int $errno 状态码
     * @param array $data 数据内容
     * @param string $msg 状态码对应的信息
     * @param string $userMsg 提示信息
     * @param string $cb JSONP回调函数名称,default: _cb
     * @return array 返回内容
     */
    protected function json($errno = 0, $data = [], $msg = null, $userMsg = null, $cb = '_cb') {
        $allowDomain = '*.jiedaibao.com';
        if (isset($_SERVER['HTTP_REFERER']))
        {
            $referer = str_replace('http://', '', $_SERVER['HTTP_REFERER']);
            $referer = str_replace('https://', '', $referer);
            $refererDomain = substr($referer, 0, strpos($referer, '/'));
            if (strpos($refererDomain, '.jiedaibao.com') !== False ||
                strpos($refererDomain, '.jiedaibao.com:8001') !== False ||
                strpos($refererDomain, '.jiedaibao.com:8002') !== False ||
                strpos($refererDomain, '.jiedaibao.com:8003') !== False )
            {
                $allowDomain = $refererDomain;
            }
        }
        header('Access-Control-Allow-Origin:http://' . $allowDomain);
        
        $retTpl = [
            'error' => [
                'returnCode' => $errno,
                'returnMessage' => $msg,
                'returnUserMessage' => $userMsg,
            ],
        ];
        if (!empty($data)) {
            $retTpl = array_merge($retTpl, ['data' => $data]);
        }
        
        if (isset($_GET[$cb]) && !empty($_GET[$cb])) {
            Yii::$app->response->format = Response::FORMAT_JSONP;
            $retTpl = [
                'callback' => urldecode($_GET[$cb]),
                'data' => $retTpl,
            ];
        } else {
            Yii::$app->response->format = Response::FORMAT_JSON;
        }
        
        if ($errno !== self::INFO_OK) {
            Yii::warning("msg[JdbController_json_reponse_str], errno[$errno], msg[$msg], userMsg[$userMsg]");
        }
        
        return $retTpl;
    }

    /**
     * 返回成功数据
     *
     * @param array $data
     * @param int $errno
     * @param string $msg
     * @param string $userMsg
     * @param string $cb
     * @return array
     */
    protected function ok($data = [], $errno = self::INFO_OK,  $msg = 'success', $userMsg = '', $cb = '_cb') {
        return $this->json($errno, $data, $msg, $userMsg, $cb);
    }

    /**
     * 返回失败数据
     *
     * @param int $errno
     * @param string $userMsg
     * @param array $data
     * @param string $msg
     * @param string $cb
     * @return array
     */
    protected function error($userMsg = '', $msg = 'error', $errno = self::INFO_ERROR, $data = [], $cb = '_cb') {
        return $this->json($errno, $data, $msg, $userMsg, $cb);
    }

    /**
     * 重写父类方式以支持Action函数名为驼峰命名方式
     *
     * @param string $id the action ID.
     * @return Action the newly created action instance. Null if the ID doesn't resolve into any action.
     */
    public function createAction($id)
    {
        $action = parent::createAction($id);
        if (!empty($action)) {
            return $action;
        }
        // 支持函数名驼峰命名方式
        if (preg_match('/^[a-z0-9\\-_]+$/i', $id) && strpos($id, '--') === false && trim($id, '-') === $id) {
            $methodName = 'action' . str_replace(' ', '', ucwords(implode(' ', explode('-', $id))));
            if (method_exists($this, $methodName)) {
                $method = new \ReflectionMethod($this, $methodName);
                if ($method->isPublic() && $method->getName() === $methodName) {
                    return new InlineAction($id, $this, $methodName);
                }
            }
        }
    
        return null;
    }
    
    /**
     * Returns POST parameter with a given name. If name isn't specified, returns an array of all POST parameters.
     *
     * @param string $name the parameter name
     * @return mixed
     */
    protected function post($name=null, $default=null) {
    	if($name === null){
    		return Yii::$app->request->post();
    	}
        return Yii::$app->request->post($name, $default);
    }
    
    /**
     * Returns GET parameter with a given name. If name isn't specified, returns an array of all GET parameters.
     *
     * @param string $name the parameter name
     * @return mixed
     */
    protected function get($name=null, $default=null) {
    	if($name === null){
    		return Yii::$app->request->get();
    	}
        return Yii::$app->request->get($name, $default);
    }
}