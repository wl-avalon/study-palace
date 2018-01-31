<?php
namespace rrxframework\base;

use rrxframework\util\DictUtil;
use rrxframework\util\ServiceWfUtil;
use yii;
use rrxframework\util\HttpUtil;
use rrxframework\util\StrUtil;

class JdbService {
	const SERVICE_REQUEST_MODE_LOCAL = 'local';
	const SERVICE_REQUEST_MODE_HTTP_GET = 'http-get';
	const SERVICE_REQUEST_MODE_HTTP_POST = 'http-post';
    const SERVICE_REQUEST_MODE_HTTP_M_GET = 'http-m-get';
    const SERVICE_REQUEST_MODE_HTTP_M_POST = 'http-m-post';

    // 返回信息记录级别
    // 部分,截取固定字符串
    const RET_LOG_LEVEL_PART = 1;
    // 不记录
    const RET_LOG_LEVEL_NO = 2;
    // 全记录
    const RET_LOG_LEVEL_ALL = 3;

    // 部分字符串最大值
    const PART_STR_MAX = 200;

	//暂时只支持local调用
	//TODO:由于时间有限暂时只要能打通功能即可，日后完善
    static public function call($module_name,$service_name,$service_params,$request_mode = self::SERVICE_REQUEST_MODE_LOCAL,$encoding = "utf-8",$data_format = "json", $auto_sign = false, array $mode_conf = array(), $retLogLevel = self::RET_LOG_LEVEL_PART) {
		if(empty($module_name) || empty($service_name)){
            ServiceWfUtil::localConfLog($module_name, ServiceWfUtil::LOCAL_CONF_ERROR_TYPE_PARAM_EMPTY, ''
                , ['module_name' => $module_name, 'service_name' => $service_name,]);
			return false;
		}
		
		switch($request_mode){
			case self::SERVICE_REQUEST_MODE_LOCAL:
				$result = self::localCall($module_name,$service_name,$service_params,$encoding,$data_format,$auto_sign,$mode_conf);
				break;
			case self::SERVICE_REQUEST_MODE_HTTP_GET:
			case self::SERVICE_REQUEST_MODE_HTTP_POST:
				$result = self::httpCall($module_name,$service_name,$service_params,$request_mode,$encoding,$data_format,$auto_sign,$mode_conf, $retLogLevel);
				break;
            case self::SERVICE_REQUEST_MODE_HTTP_M_GET:
            case self::SERVICE_REQUEST_MODE_HTTP_M_POST:
                $result = self::httpMultiCall($module_name, $service_name, $service_params, $request_mode
                    , $encoding, $data_format, $auto_sign, $mode_conf, $retLogLevel);
                break;
			default:
				return false;
		}

		return $result;
    }
    
	static protected function localCall($module_name,$service_name,$service_params,$encoding = "utf-8",$data_format = "json",$auto_sign = false,array $mode_conf){
		$class_name = "app\\modules\\service\\".ucfirst($module_name)."Service";
		if(!class_exists($class_name)){
			return false;
		}
		$obj = new $class_name;
		return $obj->invoke($service_name,$service_params,$encoding = "utf-8",$data_format = "json");
	}
	
	static protected function httpCall($module_name,$service_name,$service_params,$http_method,$encoding = "utf-8",$data_format = "json",$auto_sign = false,array $mode_conf, $retLogLevel){
        $service_conf = JdbConf::get($module_name);
		if(empty($service_conf)) {
            ServiceWfUtil::localConfLog($module_name, ServiceWfUtil::LOCAL_CONF_ERROR_TYPE_EMPTY);

			return false;
		}

        $formatConf = self::formatConf($module_name, $service_conf, $mode_conf);

        $service_name = $service_name[0] === '/' ? $service_name : '/'.$service_name;
        $url = $formatConf['domain'] . $service_name;
        $http_method = strtr($http_method, ['http-'=>'']);

        if($auto_sign && !empty($service_conf['sign_key'])){
            $service_params['sign'] = StrUtil::genSign($service_params, $service_conf['sign_key']);
        }
        
        $input = [
            'url' => $url,
            'option' => $formatConf['option'],
            $http_method => $service_params,
        ];

        $startTime = DictUtil::microtimeFloat();

		$http = HttpUtil::instance();
		$output = $http->curl($input, $module_name);

        $endTime = DictUtil::microtimeFloat();

        $logRet = '';
        if ($retLogLevel == self::RET_LOG_LEVEL_ALL) {
            $logRet = $output['content'];
        } else if ($retLogLevel == self::RET_LOG_LEVEL_PART) {
            $logRet = mb_substr($output['content'], 0, self::PART_STR_MAX);
        }

        JdbLog::addNotice("call:$module_name:$service_name", ['cost' => ($endTime - $startTime), 'ret' => $logRet]);

		JdbLog::debug("http call debug : curl '$url" . 
		        ($http_method == "get" ? "?" . http_build_query($service_params) . "'" : "' -d '" . http_build_query($service_params) . "'"));
		
		if ($output['errno'] != 0) {
			return false;
		}
		
		$result = $output['content'];
		if(strcasecmp($data_format, "json") === 0){
			$result = json_decode($output['content'], true);
			if($result === null){
                ServiceWfUtil::formatLog($module_name, ServiceWfUtil::FORMAT_ERROR_TYPE_UNPACK
                    , 'json', $url, $service_params, $result);
			}
		}
		return $result;
	}

    static protected function httpMultiCall($serviceName, $urlPath, $param, $httpMethod
        ,$encoding = "utf-8",$dataFormat = "json",$autoSign = false,array $modeConf, $retLogLevel) {
        $serviceConf = JdbConf::get($serviceName);
        if(empty($serviceConf)) {
            ServiceWfUtil::localConfLog($serviceName, ServiceWfUtil::LOCAL_CONF_ERROR_TYPE_EMPTY);

            return false;
        }

        $formatConf = self::formatConf($serviceName, $serviceConf, $modeConf);

        $urlPath = $urlPath[0] === '/' ? $urlPath : '/'.$urlPath;
        $url = $formatConf['domain'] . $urlPath;
        $httpMethod = strtr($httpMethod, ['http-m-'=>'']);

        $reqList = [];

        foreach ($param as $item) {
            $reqList[] = [
                'url' => $url,
                'option' => [
                    CURLOPT_CONNECTTIMEOUT_MS => $formatConf['option'][CURLOPT_CONNECTTIMEOUT_MS],
                    CURLOPT_TIMEOUT_MS => $formatConf['option'][CURLOPT_TIMEOUT_MS],
                ],
                $httpMethod => $item,
            ];
        }

        $startTime = DictUtil::microtimeFloat();

        $http = HttpUtil::instance();
        $output = $http->mcurl($reqList, $serviceName);

        $endTime = DictUtil::microtimeFloat();

        $retData = [];
        if (is_array($output)) {
            foreach ($output as $item) {
                $retData[] = [
                    'cost' => $item['cost'],
                    'idx' => $item['idx'],
                    'errno' => $item['errno'],
                    'msg' => $item['msg'],
                ];
            }
        }

        JdbLog::addNotice("mcall:$serviceName:$urlPath", ['cost' => ($endTime - $startTime), 'ret' => $retData]);

        if(strcasecmp($dataFormat, "json") === 0) {
            foreach ($output as $key => $item) {
                if (isset($item['errno']) && ($item['errno'] == 0)) {
                    $output[$key]['result'] = json_decode($output[$key]['content'], true);

                    if ($output[$key]['result'] === null) {
                        ServiceWfUtil::formatLog($serviceName, ServiceWfUtil::FORMAT_ERROR_TYPE_UNPACK
                            , 'json', $url, $param[$key], $output[$key]['result']);
                    }
                }
            }
        }

        return $output;
    }

    // 默认连接超时
    const DEFAULT_CONN_TIMEOUT_MS = 1200;
    // 默认超时
    const DEFAULT_TIMEOUT_MS = 6000;

    static protected function formatConf($serviceName, $conf, array $selfConf = []) {
        $formatConf = [
            'domain' => '',
            'option' => [
                CURLOPT_CONNECTTIMEOUT_MS => self::DEFAULT_CONN_TIMEOUT_MS,
                CURLOPT_TIMEOUT_MS => self::DEFAULT_TIMEOUT_MS,
                'retry' => 0,
            ],
        ];

        if (empty($conf['domain'])) {
            JdbLog::addNotice($serviceName . '_conf_domain', 0);

            $host = substr_compare($conf['host'],"http://",0,7) === 0 ? $conf['host'] : "http://" . $conf['host'];
            $port = isset($conf['port']) ? intval($conf['port']) : 80;

            $formatConf['domain'] = $host . ':' . $port;
            $formatConf['option'][CURLOPT_CONNECTTIMEOUT_MS]
                = isset($conf['conn_timeout']) && $conf['conn_timeout'] > 0
                ? intval($conf['conn_timeout']) : $formatConf['option'][CURLOPT_CONNECTTIMEOUT_MS];
            $formatConf['option'][CURLOPT_TIMEOUT_MS]
                = isset($conf['read_timeout']) && $conf['read_timeout'] > 0
                ? intval($conf['read_timeout']) : $formatConf['option'][CURLOPT_TIMEOUT_MS];
            $formatConf['option']['retry'] = isset($conf['retry_times'])
                ? intval($conf['retry_times']) : $formatConf['option']['retry'];

        } else {
            JdbLog::addNotice($serviceName . '_conf_domain', 1);

            $formatConf['domain'] = $conf['domain'];
            $formatConf['option'][CURLOPT_CONNECTTIMEOUT_MS] = isset($conf['conntimeout'])
                ? intval($conf['conntimeout']) : $formatConf['option'][CURLOPT_CONNECTTIMEOUT_MS];
            $formatConf['option'][CURLOPT_TIMEOUT_MS] = isset($conf['timeout'])
                ? intval($conf['timeout']) : $formatConf['option'][CURLOPT_TIMEOUT_MS];
            $formatConf['option']['retry'] = isset($conf['retry'])
                ? intval($conf['retry']) : $formatConf['option']['retry'];
        }

        if(!empty($selfConf)){
            $formatConf['option'] = $selfConf + $formatConf['option'];
        }
        
        JdbLog::debug($serviceName . ' conf ' . json_encode($formatConf));

        return $formatConf;
    }
}
