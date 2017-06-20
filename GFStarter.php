<?php
use Controllers\Http\Request;
use Controllers\Router\Router;
use Controllers\Router\RouteCollection;
use Controllers\RedisCacheController;
use Controllers\GFSessions\GFSessionController;
use Controllers\GFEvents\GFEventController;
use Controllers\i18nController;


class GFStarter {

	private $routerCollection;

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

	public function start() {
		$request = Request::getInstance();

		$router = new Router($this->routerCollection, $request);

		GFEventController::dispatch("Router.beforeMatch", null);
		$router->matchRequest();

		GFEventController::dispatch("Router.beforeExecute", null);
		$request->executeRequest($request);

		GFEventController::dispatch("Router.beforeSendResponse", null);
		$request->sendResponse($request);
		exit();


	}


	protected function loadModules($modules) {

		foreach ($modules as $loader) {
			new $loader($this->routerCollection);
		}
	}


}


















