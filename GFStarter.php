<?php


use Core\Controllers\Router\RouteCollection;
use Core\Controllers\GFSessions\GFSessionController;
use Core\Controllers\i18nController;
use Core\Controllers\RedisCacheController;
//use Core\Controllers\Http\Request;
use Core\Controllers\GFEvents\GFEventController;
use Core\Controllers\Http\Psr\Request;
use Core\Controllers\Router\Router;
use Core\Controllers\Router\RouteModel;
use Core\Controllers\GFEvents\GFEvent;

require_once __DIR__ .'/Core/Configs/Constants.php';
require_once 'GFAutoload.php';
if(file_exists( __DIR__ .'/Core/Vendors/autoload.php'))
	require_once __DIR__ .'/Core/Vendors/autoload.php';

class GFStarter {

	private static $routerCollection;


	function __construct() {
		self::$routerCollection = RouteCollection::getInstance();
	}

	public function init() {
		GFSessionController::startManagingSession();
		$session = GFSessionController::getInstance();

		$session->getSessionModel()->setUserLang(i18nController::getDefaultLanguage());

		if(REDIS_CACHE_ENABLED) {
			$redis = RedisCacheController::getRedisClient();
			$redisKey = 'Redis::GolgoFramework::Test';
			$redis->set($redisKey, "TEST");
			$redis->expire($redisKey, 60);
		}
	}

	public function loadModules($modules) {
	    GFEventController::triggerWithEventName("GFStarter.before.loadModules");
		foreach ($modules as $loader) {
			new $loader(self::$routerCollection);
		}
		GFEventController::triggerWithEventName("GFStarter.after.loadModules");
	}


	/** Nuevo eventos
	 *
	 	$event = new GFEvent();
		$event->setName("start");
		$gfevent = new GFEventController();
		$gfevent->attach($event, function($evento) {
		$evento->stopPropagation(false);
		print_r("llamado evento en se acaba!");
		}, 0);
		$gfevent->attach($event, function($args) {print_r("llamado evento en start222!"); die();}, 0);
	 */
	public function start() {

	    GFEventController::triggerWithEventName("Request.before.parseRequest");
		$request = Request::parseRequest();

		$router = new Router(self::$routerCollection, $request);

		GFEventController::triggerWithEventName("Router.before.matchRequest");
		$router->matchRequest();

		GFEventController::triggerWithEventName("Request.before.executeRequest");
		$request->executeRequest();

		GFEventController::triggerWithEventName("Request.before.sendResponse");
		$request->sendResponse();
		exit();


	}


	/**
	 *
	 * @param array $method use all for any method
	 * @param string $url
	 * @param string|callable $class
	 * @param string $classMethod
	 * @param string $csrf
	 * @param string $name
	 */

	public static function withRoute($method, $url, $class, $classMethod = null, $csrf = false, $name = "") {
		$config = array();
		if($method == "all") $method = array();
		if(!is_array($method)) $method = array($method);

		$config["name"] = $name;
		$config["checkCSRF"] = $csrf;
		$config["verbs"] = $method;

		if(is_callable($class)) {
			$config["targetClass"] = null;
			$config['targetClassMethod'] = null;
			$route = RouteModel::withConfig($url, $config);
			$route->setFunction($class);
		} else {
			$config["targetClass"] = $class;
			$config['targetClassMethod'] = $classMethod;
			$route = RouteModel::withConfig($url, $config);
		}

		self::$routerCollection->attachRoute($route);
	}




}


















