<?php

define('IP', '127.0.0.1');
define('PORT', 8888);
define('WORK_NUM', 50);
define('DAEMONIZE', false);
define('MAX_REQUEST', 20000);
define('DISPATCH_MODE', 1);

session_start();

class HttpServer
{
	public static $instance;

	public $http;
	public static $get;
	public static $post;
	public static $header;
	public static $server;
	private $application;

	public function __construct() {
		$http = new swoole_http_server(IP,PORT);

		$http->set(
			[
				'worker_num' => WORK_NUM,
				'daemonize' => DAEMONIZE,
				'max_request' => MAX_REQUEST,
			        'dispatch_mode' => DISPATCH_MODE
			]
		);

		$http->on('request', function ($request, $response) {

			if (stripos($request->server['path_info'], 'favicon.ico') !== false || stripos($request->server['request_uri'], 'favicon.ico') !== false) {
				return $response->end();
			}
		
			HttpServer::$server = [];
			HttpServer::$header = [];
			HttpServer::$get = [];
			HttpServer::$post = [];

			if (isset($request->server)) {
				HttpServer::$server = $request->server;
				if (isset(HttpServer::$server['request_uri']) && HttpServer::$server['request_uri'] == '/index.php') {
					HttpServer::$server['request_uri'] = '/';
				}
			}

			if (isset($request->header)) {
				HttpServer::$header = $request->header;
			}

			if (isset($request->get)) {
				HttpServer::$get = $request->get;
			}

			if (isset($request->post)) {
				HttpServer::$post = $request->post;
			}

			$errorInfo = null;
			$dispatchObj = null;
			$responseObj = null;
			try {
				$yaf_request = new Yaf_Request_Http(HttpServer::$server['request_uri']);
				$dispatchObj = $this->application->getDispatcher();
				$responseObj = $dispatchObj->returnResponse(true)->dispatch($yaf_request);
			} catch ( Yaf_Exception $e ) {
				$errorInfo = 'error request';
			}


			//检查是否有跳转请求
			$cache = Cache::getInstance('m');
			$key = 'mem_'.session_id();
			$sessionCache = $cache->get($key);

			if (empty($sessionCache)) {
				$sessionCache = [];
			}


			if (isset($sessionCache[$key]['location'])) {
				$locationUrl = $sessionCache[$key]['location'];
				unset($sessionCache[$key]['location']);
				$cache->set($key, $sessionCache, 86400);
				$response->status(302);
				//$response->cookie(session_name(), session_id());
				$response->header("Location", $locationUrl);
			}
			//end

			ob_start();
			if (!is_null($errorInfo) || empty($responseObj)) {
				echo 'error info',$errorInfo;
			} else {
				$responseObj->response();
			}
			$result = ob_get_contents();
		  	ob_end_clean();

			$response->header("X-Server", "Rain Web Server");
			$response->header("Content-Type", "text/html; charset=utf-8");
			//$response->cookie(session_name(), session_id());
		  	$response->end($result);
		});

		$http->on('WorkerStart', function ($request, $response) {
			define('APPLICATION_PATH', dirname(__DIR__));
			$this->application = new Yaf_Application( APPLICATION_PATH."/conf/application.ini");
			ob_start();
			$this->application->bootstrap()->run();
			ob_end_clean();
		});

		$http->start();
	}

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new HttpServer;
		}
		return self::$instance;
	}
}

HttpServer::getInstance();
