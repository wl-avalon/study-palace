<?php

namespace ploanframework\actions;

use ploanframework\apis\ApiContext;
use ploanframework\apis\helper\ApiHelper;
use ploanframework\components\AlertException;
use ploanframework\components\Assert;
use ploanframework\components\AuthException;
use ploanframework\components\BusinessException;
use ploanframework\components\Formatter;
use ploanframework\constants\JdbConsts;
use ploanframework\constants\JdbErrors;
use ploanframework\services\auth\AuthService;
use ploanframework\services\passport\PassportService;
use ploanframework\services\risk\RiskService;
use ploanframework\utils\Arr;
use rrxframework\base\JdbAction;
use rrxframework\base\JdbException;
use rrxframework\base\JdbLog;
use rrxframework\base\JdbModule;

/**
 * Class BaseAction
 * @package ploanframework\actions
 * @author wangdj
 */
abstract class BaseAction extends JdbAction{
    protected $returnCode, $output, $auc, $returnUserMessage, $returnMessage;
    protected $popUpRouter;
    protected $check_web_login = false;
    /**
     * 入参
     * @var array|null
     */
    protected $params = null;
    
    public function __construct($id, $controller, $config = []){
        parent::__construct($id, $controller, $config);
        if(WEB_TYPE == JdbConsts::WEB_TYPE_INNER){
            $this->check_inner = true;
            $this->check_login = false;
            $this->check_web_login = false;
        }

        ApiHelper::initApiConfig();
    }

    /**、
     * 入参校验规则
     * @return array
     */
    abstract public function getRules(): array;

    /**
     * 返回值格式化规则
     * @return array
     */
    //abstract public function getFormat(): array;

    /**
     * 获取参数
     * @param string|null $name
     * @param string|null $default
     * @return array|mixed|null
     */
    protected function get($name = null, $default = null){
        if(empty($this->params)){
            $this->params = parent::get();
        }

        return Arr::get($this->params, $name, $default);
    }

    /**
     * 获取鉴权类型
     * @return int
     */
    protected function getAuthType(){
        return 0;
    }

    /**
     * 执行前健全校验
     * @throws AuthException
     */
    public function beforeExecute(){
        $authType = $this->getAuthType();

        if($authType > 0){
            
            if (empty($this->get('eventID'))) {
                $riskData = RiskService::getPop($this->get('memberID'), $authType, $this->get());
                if ($riskData && isset($riskData['popUpRouter']) && !empty($riskData['popUpRouter'])) {
                    $this->popUpRouter = $riskData['popUpRouter'];
                    throw new BusinessException(JdbErrors::ERR_NO_SUCCESS, null, '需要确认');
                }    
            }
            
            $this->params = AuthService::checkAuth($authType, $this->get());
        }
    }

    /**
     * 初始化参数
     */
    public function setParams(){
    }

    public function insideRun(){

    }

    public function run(){
        $response = null;
        try{
            JdbLog::addNotice("mdc_startTime", $this->getMillisTime());

            if(!defined("ENV") || ENV == 'prod' || !$this->get('_d', 0)){
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

            if($this->check_web_login){
                $this->checkWebLogin();
            }

            // 在执行主函数之前调用参数校验函数
            $this->checkParam();
            $this->setParams();
            $response = $this->execute();
            $this->afterExecute();
            $this->returnCode = JdbErrors::ERR_NO_SUCCESS;
            $this->output = $response;
            $this->returnUserMessage = $this->returnUserMessage ?? '操作成功';
            $this->returnMessage = $this->returnMessage ?? '操作成功';
        }catch(AlertException $e){
            $this->returnCode = $e->getCode();
            $this->returnUserMessage = $e->getMessage();
            $this->returnMessage = $e->getSysMessage();
            $this->output = $e->getErrInfo();
            $this->popUpRouter = $e->getPopUpRouter();
        }catch(BusinessException $e){
            $this->returnCode = $e->getCode();
            $this->returnUserMessage = $e->getMessage();
            $this->returnMessage = $e->getSysMessage();
            $this->output = $e->getErrInfo();
            $file = $e->getFile();
            $line = $e->getLine();
            JdbLog::notice("catch BusinessException errno[{$this->returnCode}] errInfo [" . json_encode($this->output) . "] userMsg[{$this->returnUserMessage}] sysMsg[$this->returnMessage] file[$file] line[$line]");
        }catch(AuthException $e){
            $this->returnCode = $e->getCode();
            $this->returnUserMessage = $e->getMessage();
            $this->returnMessage = $e->getSysMessage();
            $this->output = $e->getErrInfo();
            $this->auc = $e->getAuthInfo();

            $msg = 'auth progress -- ';
            foreach($e->getAuthInfo() as $key => $value){
                $msg .= " {$key}[{$value}]";
            }
            JdbLog::notice($msg);
        }catch(JdbException $e){
            $sysMsg = $e->getSysMessage();
            $usrMsg = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();

            $this->returnCode = $e->getCode();
            $this->returnMessage = $sysMsg;
            $this->returnUserMessage = empty($usrMsg) ? JdbErrors::getUserMsg($this->returnCode) : $usrMsg;
            $this->output = $e->getErrInfo();
            JdbLog::warning("catch JdbException errno[{$this->returnCode}] errInfo [" . json_encode($e->getErrInfo()) . "] userMsg[{$this->returnUserMessage}] sysMsg[$sysMsg] file[$file] line[$line]");
            JdbLog::addNotice("ao_errno", $e->getCode());
            JdbLog::addNotice("ao_errmsg", $this->returnUserMessage);

        }catch(\PDOException $e){
            $this->handleError($e);
        }catch(\Exception $e){
            $this->handleError($e);
        }catch(\Error $e){
            $this->handleError($e);
        }finally{
            $this->setResponse();
            $module = JdbModule::getModuleName();
            JdbLog::notice("{$module}_returns[" . json_encode($this->response_data) . "]");
            return $this->render();
        }
    }

    /**
     * 执行后返回值格式化及验证
     */
    public function afterExecute(){
        ApiContext::clean();
    }

    public function checkWebLogin(){
        if($this->check_web_login === false){
            return true;
        }
        $accessToken = $_COOKIE['accessToken'] ?? "";
        Assert::isTrue(!empty($accessToken), "请登陆后重试");
        $checkResponse = PassportService::checkWebAccessToken($accessToken);
        Assert::isTrue($checkResponse->success() && !empty($checkResponse['memberID']), "请登陆后重试");

        //这里不能直接往$this->params写,如果是第一次用params的话,params会无法被请求参数初始化,原因参考这个类的get方法
        if(empty($this->params)){
            $_POST['memberID'] = $checkResponse['memberID'];
        }else{
            $this->params['memberID'] = $checkResponse['memberID'];
        }
        return true;
    }

    /**
     * 封闭掉
     * @param $response
     */
    final public function afterExcute($response){
    }

    /**
     * 参数校验
     * @throws JdbException
     */
    final public function checkParam(){
        $this->beforeExecute();
        Formatter::validate($this->get(), $this->getRules());
    }

    protected function setResponse(){
        $errNo = isset($this->returnCode) ? $this->returnCode : JdbErrors::ERR_NO_UNKNOWN;
        JdbLog::setErrno($errNo);
        $ret = [
            'error' => [
                'returnCode'        => strval($errNo),
                'returnMessage'     => $this->returnMessage,
                'returnUserMessage' => $this->returnUserMessage,
            ],
            'data'  => is_null($this->output) ? null : $this->output,
            'logId' => JdbLog::getLogID(),
        ];

        if(!empty($this->auc)){
            $ret['auc'] = $this->auc;
        }
        if(!empty($this->popUpRouter)){
            $ret['popUpRouter'] = $this->popUpRouter;
        }
        header("jdb_errno:{$errNo}");
        $this->response_data = $ret;
    }

    private function handleError(\Throwable $e){
        $file = $e->getFile();
        $line = $e->getLine();
        JdbLog::warning("catch Exception errno[{$e->getCode()}]  msg[{$e->getMessage()}] file[$file] line[$line]");
        JdbLog::addNotice("ao_errno", $e->getCode());
        JdbLog::addNotice("ao_errmsg", $e->getMessage());
        $this->returnCode = JdbErrors::ERR_NO_SERVER_BUSY;
        $this->returnMessage = $e->getMessage();
        $this->returnUserMessage = JdbErrors::getUserMsg($this->returnCode);
        $this->output = null;
    }
}