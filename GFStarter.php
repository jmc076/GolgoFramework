<?php
use Controllers\Http\Request;
use Controllers\Http\Response;
use Controllers\Router\Router;
use Controllers\SessionController;
use Controllers\Router\RouteCollection;
use Controllers\Events\EventController;
use Controllers\RedisCacheController;


require_once __DIR__ . '/bootstrap.php';

class GFStarter {
	
	private $routerCollection;
	
	function __construct(RouteCollection $routerCollection) {
		
		global $session;
		$session = new SessionController();
		$session->initSession();
		
		global $localization;
		$localization = $this->getDefaultLanguage();
		
		$this->routerCollection = $routerCollection;
		
		if(REDIS_CACHE_ENABLED) {
		    $redis = RedisCacheController::getRedisClient();
		    $redisKey = 'Redis::GolgoFramework::Test';
		    $redis->set($redisKey, "TEST");
		    $redis->expire($redisKey, 60);
		}
		
		
		
	}
	
	function start() {
		$request = new Request();
		$response = new Response();
		
		$this->loadModules();
		
		$router = new Router($this->routerCollection, $request);
		
		EventController::dispatch("Router.beforeParse", array("request"=>$request, "response" => $response, "router" => $router));
		$router->parseRequest();
		
		EventController::dispatch("Router.beforeDispatch", array("request"=>$request, "response" => $response, "router" => $router));
		$response->dispatchRequest($request);
	}
	
	
	
	private function loadModules() {
	
		$moduleLoaders = array();
		$moduleLoaders[] = 'Modules\UserManagement\Bootstrap';
		$moduleLoaders[] = 'Modules\Tivoli\Bootstrap';
	
	
		foreach ($moduleLoaders as $loader) {
			new $loader($this->routerCollection);
		}
	}
	
	function getDefaultLanguage() {
		if(isset($_SESSION["lang"]) && $_SESSION["lang"] != "") {
			return $_SESSION["lang"];
		} else {
			if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"]))
				return $this->parseDefaultLanguage($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
				else
					return $this->parseDefaultLanguage(NULL);
		}
	}
	
	function parseDefaultLanguage($http_accept, $deflang = "en") {
		if(isset($http_accept) && strlen($http_accept) > 1)  {
			# Split possible languages into array
			$x = explode(",",$http_accept);
			foreach ($x as $val) {
				#check for q-value and create associative array. No q-value means 1 by rule
				if(preg_match("/(.*);q=([0-1]{0,1}.\d{0,4})/i",$val,$matches))
					$lang[$matches[1]] = (float)$matches[2];
					else
						$lang[$val] = 1.0;
			}
	
			#return default language (highest q-value)
			$qval = 0.0;
			foreach ($lang as $key => $value) {
				if ($value > $qval) {
					$qval = (float)$value;
					$deflang = $key;
				}
			}
		}
		return strtolower($deflang);
	}
	
	
}


















