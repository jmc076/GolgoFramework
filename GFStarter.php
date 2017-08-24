<?php


use Core\Controllers\Router\RouteCollection;
use Core\Controllers\GFSessions\GFSessionController;
use Core\Controllers\i18nController;
use Core\Controllers\RedisCacheController;
//use Core\Controllers\Http\Request;
use Core\Controllers\GFEvents\GFEventController;
use Core\Controllers\Http\Psr\Request;
use Core\Controllers\Router\Router;

class GFStarter {

	private $routerCollection;

	public static $request;

	function __construct(RouteCollection $routerCollection) {

		GFSessionController::startManagingSession();
		$session = GFSessionController::getInstance();

		$session->getSessionModel()->setUserLang(i18nController::getDefaultLanguage());



		$this->routerCollection = $routerCollection;

		if(REDIS_CACHE_ENABLED) {
		    $redis = RedisCacheController::getRedisClient();
		    $redisKey = 'Redis::GolgoFramework::Test';
		    $redis->set($redisKey, "TEST");
		    $redis->expire($redisKey, 60);
		}



	}

	public function initModules($modules) {
		$this->loadModules($modules);
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

		self::$request = Request::parseRequest();


		$router = new Router($this->routerCollection, self::$request);

		GFEventController::dispatch("Router.beforeMatch", null);
		$router->matchRequest();

		GFEventController::dispatch("Router.beforeExecute", null);
		self::$request->executeRequest();

		GFEventController::dispatch("Router.beforeSendResponse", null);
		self::$request->sendResponse();
		exit();


	}


	protected function loadModules($modules) {

		foreach ($modules as $loader) {
			new $loader($this->routerCollection);
		}
	}


}


















