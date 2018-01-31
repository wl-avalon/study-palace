<?php
namespace rrxframework\base;
use Yii;
use yii\base\Action;
use rrxframework\base\JdbLog;
use rrxframework\base\JdbException;
use rrxframework\base\JdbService;
use rrxframework\base\JdbSession;
use rrxframework\base\JdbModule;
use rrxframework\base\JdbConf;
use rrxframework\util\IpUtil;
use yii\web\Response;
use rrxframework\util\ServiceWfUtil;
use rrxframework\util\DictUtil;

/**
 * 借贷宝动作基类
 * 
 * @author gaozh@jiedaibao.com
 */
abstract class JdbAction extends Action
{

	const REQUEST_METHOD_POST = 1;
	const REQUEST_METHOD_GET = 2;
	const REQUEST_METHOD_GETPOST = 3;
	
	const RENDER_TYPE_JSON = 'json';
	const RENDER_TYPE_PAGE = 'page';
	const RENDER_TYPE_HTML = 'html';
	// 输出方式 302
	const RENDER_TYPE_REDIRECT = 'redirect';
	
	const DEFAULT_APPKEY = "fb371c48e9a9b2a1174ed729ae888513";
	
	const INFO_OK = 0;
	const INFO_ERROR = 1;
	const ERR_NO_SUCCESS = 0;
	const ERR_NO_SERVER_BUSY = 100000;
	const ERR_NO_UNKNOWN = 100001;
	const ERR_NO_PDO_EXCEPTION = 100100;
	const ERR_NO_PARAM_INVALID = 200000;
	const ERR_NO_INNER_FAILED = 300003;
	const ERR_NO_NEED_THIRD_AUTH = 503108;
    const ERR_NO_NEED_THIRD_BIND = 503109;
    
	const ACCESS_TOKEN_KEEPTIME = 432000;//60 * 24 * 60 * 60;//access_token有效时间长度，单位秒
	
	static public $secret_keys = [
	    'loginPassword', 'newPassword', 'oldPassword', 'password',
	];
	
	// 是否需要登陆校验
	protected $check_login = true;
	// 是否需要第三方授权校验
	protected $check_third_auth = false;
	// 是否需要内网校验
	protected $check_inner = false;
	// 是否校验appKey（兼容java接口）
	protected $check_appKey = false;
	// 是否校验app_id与app_key（appKey新的校验方案）
	protected $check_app_id_and_app_key = false;
	
	// 请求方法，默认get或post请求
	protected $request_method = self::REQUEST_METHOD_GETPOST;
	
	// 接口输出格式，默认json
	protected $render_type = self::RENDER_TYPE_JSON;
	
	//jsonp回调的参数名，为null表示不支持jsonp
	protected $jsonp_callback = null;
	// 登陆用户id
	protected $user_id = 0;
	
	// 模版输出的tpl名字
	protected $tpl_name = null;
	protected $redirect_url = '';
	// 输出的结果数据
	protected $response_data = [];

	// 错误页地址
	protected $error_url = "https://app.jiedaibao.com/toast/error.html?msg=";

	protected $static_host = "https://app.jiedaibao.com";

    protected $quailtyBusinessType = 0;
    protected $errno = 0;
    protected $serviceID = '';
    protected $transactionID = '';
    protected $qualityMemberId = '';
    protected $busiAmount = 0;
	
    /**
     * @desc execute主函数执行之前进行传递的参数校验操作
     */
    public function checkParam() {}

	abstract function execute();
	
	function init(){
		$session_id = JdbLog::getLogID();//JdbSession::getSessionId();
		
		//兼容java
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
		JdbLog::addNotice("mdc_uri", $uri);
		JdbLog::addNotice("mdc_traceId", $session_id);
		//JdbLog::addNotice("mdc_logId", JdbLog::getLogID());
		JdbLog::addNotice("mdc_clientIP", IpUtil::getClientIp());
		
		$memberId = $this->get("memberID");
		if(!empty($memberId)){
		    //$this->memberId = $memberId;
			JdbLog::addNotice('mdc_userNo', $memberId);
		}
			
		$platform = $this->get('platform');
		if(!empty($platform)){
			JdbLog::addNotice("mdc_platform", $platform);
		}
			
		$clientVersion = $this->get('clientVersion');
		if(!empty($clientVersion)){
			JdbLog::addNotice("mdc_clientVersion", $clientVersion);
		}
			
		$channel = $this->get('channel');
		if(!empty($channel)){
			JdbLog::addNotice("mdc_channel", $channel);
		}
			
		$udid = $this->get('udid');
		if(!empty($udid)){
			JdbLog::addNotice('mdc_udid', $udid);
		}
			
		$inBackground = $this->get('inBackground',"0");
		JdbLog::addNotice("mdc_inBG", $inBackground);
		
		//打印输入数据
		$input = $this->get();
		foreach($input as $key => $value){
			
			JdbLog::addNotice("ai_".$key, in_array($key, self::$secret_keys) ? '***' : (is_array($value) ? serialize($value) : $value));
		}
	}
	
	function exception($e){
		$errno = $e->getCode();
		$errmsg = $e->getMessage();
		$file = $e->getFile();
		$line = $e->getLine();
		$sysmsg = null;
		$errinfo = null;
		if($e instanceof JdbException){
			$errinfo = $e->getErrInfo();
			$sysmsg = $e->getSysMessage();
			JdbLog::addNotice("ao_errinfo", $errinfo);
		}else if($e instanceof \PDOException){
			JdbLog::warning("pdo exception.[errinfo=" . serialize($e->errorInfo()) ."][errno=" . $errno . "][errmsg=" . $errmsg . "]");
			//pdoexception的code为字符串
			$errno = self::ERR_NO_PDO_EXCEPTION;
			$errmsg = "系统繁忙";
		}else if(intval($errno) == 0 || $e instanceof \yii\base\Exception){//未知异常，重置错误号
		    JdbLog::warning("exception.[errno=" . $errno . "][errmsg=" . $errmsg . "]");
			$errno = self::ERR_NO_UNKNOWN;
			$errmsg = "系统繁忙";
		}
		
		JdbLog::addNotice("ao_errno", $errno);
		JdbLog::addNotice("ao_errmsg", $errmsg);
		JdbLog::warning("catch exception [errno=$errno][errinfo=".serialize($errinfo)."][usermsg=$errmsg][sysmsg=$sysmsg][file=$file][line=$line]");
		
		$this->setResponseData($errno, $errinfo, empty($errmsg) ? null : $errmsg, $sysmsg);
	}
	
	public function afterExcute($response){

	}
	
	public function getMillisTime(){
		return intval(microtime(true) * 1000);
	}
	
    public function run() {
    	$response = null;
      $ssStartTime = DictUtil::microtimeFloat();
		try {
			JdbLog::addNotice("mdc_startTime", $this->getMillisTime());
			
			if(!defined("YII_ENV") || YII_ENV == 'prod' || !$this->get('_d', 0)){	
			    if($this->check_inner){
			        $this->checkInner();
			    }
			    
    			if($this->check_appKey){
    				$this->checkAppKey();
    			}
    			
    			if($this->check_app_id_and_app_key){
    				$this->checkAppIdAndAppKey();
    			}
			}
			
			if($this->check_login){
				$this->checkLogin();
			}

			if($this->check_third_auth){
			    $this->checkThirdAuth();
			}
			
            // 在执行主函数之前调用参数校验函数
            $this->checkParam();

			$response = $this->execute();
		} catch (\Exception $e) {
            $this->errno = $e->getCode();
			$this->exception($e);
		}
		$ssEndTime = DictUtil::microtimeFloat();
    ServiceWfUtil::noticeLog($this->errno, $ssStartTime, $ssEndTime, $this->quailtyBusinessType, $this->serviceID, $this->transactionID, $this->qualityMemberId, $this->busiAmount);
    $this->afterExcute($response);
		return $this->render($response);
		
    }
    
    protected function render($response = null){
    	if(is_array($response)){
    		$this->response_data = $response;
    	}
    	
    	if($this->render_type === self::RENDER_TYPE_JSON){
    		return $this->renderJson();
    	} else if($this->render_type === self::RENDER_TYPE_PAGE){
    		return $this->renderPage();
    	} else if($this->render_type === self::RENDER_TYPE_REDIRECT){
    		return $this->renderRedirect();
    	} else if ($this->render_type === self::RENDER_TYPE_HTML) {
            return $this->renderHtml($response);
		}
    }
    
    protected function renderRedirect() {
    	if(empty($this->redirect_url)){
    		header('HTTP/1.1 404 Not Found');
    	}else{
    		header('Location: ' . $this->redirect_url);
    	}
    }
    
    protected function renderJson(){
    	$allowDomain = '*.jiedaibao.com';
        $is_https = false;
    	if (isset($_SERVER['HTTP_REFERER']))
    	{
    		$referer = str_replace('http://', '', $_SERVER['HTTP_REFERER']);
    		$referer = str_replace('https://', '', $referer);
    		$refererDomain = substr($referer, 0, strpos($referer, '/'));
    		if (strpos($refererDomain, '.jiedaibao.com') !== False ||
    				strpos($refererDomain, '.jiedaibao.com.cn') !== False)
    		{
    			$allowDomain = $refererDomain;
    		}
    	   $is_https = strncasecmp($_SERVER['HTTP_REFERER'], "https://", 8) === 0 ? true : false;
        }
        
    	
    	header('Access-Control-Allow-Origin:' . ($is_https ? 'https://' : 'http://') . $allowDomain);
    	header("Access-Control-Allow-Credentials: true");
    	
    	//jsonp
    	if(!empty($this->jsonp_callback) && $this->get($this->jsonp_callback) !== null){
    		$this->response_data = [
    			'callback' => urldecode($this->get($this->jsonp_callback)),
    			'data' => $this->response_data,
    		];
    		Yii::$app->response->format = Response::FORMAT_JSONP;
    	}else{
    		Yii::$app->response->format = Response::FORMAT_JSON;
    	}
    	
    	// TODO:data chuli
    	return $this->response_data;
    }
    
    protected function renderPage(){
    	if(!isset($this->response_data['error']['returnCode']) || $this->response_data['error']['returnCode'] != 0){
    		$error_url = $this->error_url;
    		if(!empty($this->response_data['error']['returnUserMessage'])){
    		    $error_url = $error_url . urlencode($this->response_data['error']['returnUserMessage']);
    		}
    		header("application/json;UTF-8");
    		header("Location: $error_url");
    		return;
    	}
    	

    	
    	if(empty($this->tpl_name)){
    	    JdbLog::warning("tpl_name is not found.");
    	    return;
    	}
    	
    	$tpl_path = Yii::$app->basePath . '/modules/tpl/' . $this->tpl_name;
    	if(!is_readable($tpl_path)){
    	    JdbLog::warning("tpl can not read. [tpl=".$tpl_path."]");
    	    return ;
    	}
    	
    	$ret = is_array($this->response_data['data']) ? $this->response_data['data'] : [];
    	$resource = JdbConf::get('resource');
    	$ret['__static_host'] = isset($resource['static_host']) ? strval($resource['static_host']) : $this->static_host;

    	Yii::$app->response->format = Response::FORMAT_HTML;
    	header("Access-Control-Allow-Origin", "*");

    	include($tpl_path);
    }

    /**
     * 返回HTML代码(不做任何处理)
     *
     * @param string $content html code
     * @return string
     */
    private function renderHtml($content = null) {
        Yii::$app->response->format = Response::FORMAT_HTML;
        // 优先使用传进来的参数
        if (!empty($content) && is_string($content)) {
            return $content;
        } else if (!empty($this->response_data) && is_string($this->response_data)) {
            return $this->response_data;
        }
    }

    /**
     * 返回HTML代码
     *
     * @param string $html
     * @return string
     */
    protected function html($html, $errno = self::INFO_OK) {
        JdbLog::setErrno($errno);
        $this->render_type = self::RENDER_TYPE_HTML;
        $this->response_data = $html;
    }
    
    public function setResponseData($err_no, $err_info = null, $err_user_msg = null, $err_sys_msg = null, $pop_data = null){
    	
    	JdbLog::setErrno($err_no);
    	
    	if($err_info === ""){
    		$err_info = null;
    	}
    	
    	$ret = [
    		'error' => [
    			'returnCode' => strval($err_no),
    			'returnMessage' => $err_sys_msg,
    			'returnUserMessage' => $err_user_msg,
    		],
    		'data' => $err_info,
    	    'logid' => JdbLog::getLogID(),
    	];


    	if (($pop_data !== null) && is_array($pop_data)) {
    	    $ret['popUpRouter'] = $pop_data;
        }

        header("jdb_errno:$err_no");
    	$this->response_data = $ret;
    }
    
    public function setTemResponseData($err_no, $err_info = [], $err_user_msg = null, $err_sys_msg = null){
         
        JdbLog::setErrno($err_no);
         
        if($err_info == ""){
            $err_info = [];
        }
         
        $ret = [
            'error' => [
                'returnCode' => strval($err_no),
                'returnMessage' => $err_sys_msg,
                'returnUserMessage' => $err_user_msg,
            ],
        ];
        
        $ret = array_merge($ret, $err_info);
        
        header("jdb_errno:$err_no");
        $this->response_data = $ret;
    }
    
    /**
     * 验证当前用户登录有效性
     * 
     * @return int 0:login success;1: login fail
     */
    protected function checkLogin() {
        if($this->check_login == false) {
            return;
        }

        $memberId = $this->get('memberID');

        if(!defined("YII_ENV") || YII_ENV == 'prod' || !$this->get('_d', 0)){
            $udid = $this->get('udid');
            $accessToken = $this->get('accessToken');
            
            if(empty($memberId) || -1 == $memberId || empty($udid)){
    //         	throw new JdbException(107, null, "非法请求！", "操作失败！");
            	throw new JdbException(1, null, "非法请求！", "操作失败！");
            } 
    
            $params = [
            	'memberID' => $memberId,
            	'udid' => $udid,
            	'accessToken' => $accessToken,
            	'appKey' => self::DEFAULT_APPKEY,
            ];
            $result = JdbService::call("session", "/mybankv21/member/checkLogin", $params, JdbService::SERVICE_REQUEST_MODE_HTTP_POST);
            if($result === false){
            	JdbLog::warning("call /mybankv21/member/checkLogin of session return false");
            	if($this->checkAccessTokenLocally($accessToken, $memberId) === false){
    //         		throw new JdbException(102, null, "请先登入", "操作失败");
            		throw new JdbException(1, null, "系统错误", "系统错误，请重试");
            	}
            }else{
    	        if(empty($result) || !isset($result['error']['returnCode'])){
    	        	JdbLog::warning("call /mybankv21/member/checkLogin of session error [input=".serialize($params)."][output=".serialize($result)."]");
    	        	throw new JdbException(self::ERR_NO_SERVER_BUSY, $this->get());	
    	        }
    	        if($result['error']['returnCode'] != 0){
    	        	throw new JdbException($result['error']['returnCode'], null, $result['error']["returnUserMessage"], $result['error']["returnMessage"]);
    	        }
    	        
            }
        }
        //登出成功后的处理
        $this->user_id = $memberId;

        JdbLog::addNotice('user_id', $this->user_id);
    }
    
    protected function checkThirdAuth(){
        if($this->check_third_auth == false) {
            return;
        }
        
        $authorizer = $this->get('authorizer');
        $auth_token = empty($_COOKIE['auth_token']) ? $this->get('auth_token') : $_COOKIE['auth_token'];
        $outer_open_id = empty($_COOKIE['outer_open_id']) ? $this->get('outer_open_id') : $_COOKIE['outer_open_id'];
        
        if(empty($authorizer)){
            throw new JdbException(107, null, "非法请求，授权方参数缺失！", "操作失败，授权方参数缺失！");
        }
        
        if(empty($auth_token) || empty($outer_open_id)){
            throw new JdbException(self::ERR_NO_NEED_THIRD_AUTH, null, "请先授权", "操作失败");
        }
        
        $params = [
            'authorizer' => $authorizer,
            'outer_open_id' => $outer_open_id,
            'auth_token' => $auth_token,
            'client_ip' => IpUtil::getClientIp(),
        ];
        $result = JdbService::call("session", "/mybankv21/phppassport/v2/passport/outer/checkauth", $params, JdbService::SERVICE_REQUEST_MODE_HTTP_POST);
        if($result === false || empty($result) || !isset($result['error']['returnCode'])){
            JdbLog::warning("call /mybankv21/phppassport/v2/passport/outer/checkauth of session error [input=".serialize($params)."][output=".serialize($result)."]");
            throw new JdbException(self::ERR_NO_SERVER_BUSY, $this->get());
        }
        
        if($result['error']['returnCode'] == self::ERR_NO_NEED_THIRD_AUTH){
            throw new JdbException($result['error']['returnCode'], isset($result['data']) ? $result['data'] : null, $result['error']["returnUserMessage"], $result['error']["returnMessage"]);
        }
        
        if($result['error']['returnCode'] != self::ERR_NO_SUCCESS){
            JdbLog::warning("call /mybankv21/phppassport/v2/passport/outer/checkauth of session error [input=".serialize($params)."][output=".serialize($result)."]");
            throw new JdbException($result['error']['returnCode'], isset($result['data']) ? $result['data'] : null, $result['error']["returnUserMessage"], $result['error']["returnMessage"]);
        }
        
    }
    
    protected function checkInner() {
    	if ($this->check_inner == false) {
    		return;
    	}
    
    	if (!IpUtil::checkInnerIp(IpUtil::getClientIp()) && (YII_ENV != 'dev')) {
    		$error_info = array(
    				'inner_ip' => 0,
    		);
    		throw new JdbException(self::ERR_NO_INNER_FAILED, $error_info);
    	}
    }

    protected function checkAccessTokenLocally($access_token, $user_id){
    	$index = strpos($access_token, $user_id);
    	if($index === false){
    		return false;
    	}
    	$long_millistime = substr($access_token, $index + strlen($user_id));
    	if(strlen($long_millistime) < 13){
    		return false;
    	}
    	
    	$cur_millistime = intval(microtime(true) * 1000);
    	if($cur_millistime < $long_millistime || $cur_millistime >= $long_millistime + self::ACCESS_TOKEN_KEEPTIME * 1000){
    		return false;
    	}
    	
    	return true;
    }
    
    protected function checkAppKey(){
        $appKey = $this->get("appKey");
        if(empty($appKey) || $appKey != self::DEFAULT_APPKEY){
            JdbLog::warning("appKey is invalid");
            throw new JdbException(105, null, "非法请求!", "操作失败!");
        }
    }
    
    protected function checkAppIdAndAppKey(){
    	$app_id = $this->get("app_id");
    	$app_key = $this->get("app_key");
    	if(!is_numeric($app_id) || empty($app_key)){
    		JdbLog::warning("app_id or app_key is invalid");
    		throw new JdbException(self::ERR_NO_PARAM_INVALID);
    	}
    	$resource = JdbConf::get("resource");
    	if(empty($resource['app'])){
    		JdbLog::warning("app config is loss");
    		throw new JdbException(self::ERR_NO_PARAM_INVALID);
    	}
    	$app = $resource['app'];
    	
    	if(empty($app[$app_id]['app_key']) || $app[$app_id]['app_key'] != $app_key){
    		JdbLog::warning("app_id and app_key not match");
    		throw new JdbException(self::ERR_NO_PARAM_INVALID);
    	}
    }
    
    protected function get($name=null, $default=null) {
    	if($this->request_method === self::REQUEST_METHOD_GETPOST){
    		if($name === null){
    			return array_merge(Yii::$app->request->get(), Yii::$app->request->post());
    		}else if(($ret = Yii::$app->request->post($name, null)) !== null){
    			return $ret;
    		}else{
    			return Yii::$app->request->get($name, $default);
    		}
    	}else if($this->request_method === self::REQUEST_METHOD_GET){
    		return $name === null ? Yii::$app->request->get() : Yii::$app->request->get($name, $default);
    	}else if($this->request_method === self::REQUEST_METHOD_POST){
    		return $name === null ? Yii::$app->request->post() : Yii::$app->request->post($name, $default);
    	}
    	
    	return false;
    }
    
}
