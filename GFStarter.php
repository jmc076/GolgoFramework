<?php


use Core\Controllers\Router\RouteCollection;
use Core\Controllers\GFSessions\GFSessionController;
use Core\Controllers\i18nController;
use Core\Controllers\RedisCacheController;
//use Core\Controllers\Http\Request;
use Core\Controllers\GFEvents\GFEventController;
use Core\Controllers\Router\Router;
use Core\Controllers\Http\Psr\Body;
use Core\Controllers\Http\Psr\Stream;
use Core\Controllers\Http\Psr\Uri;
use Core\Controllers\Http\Psr\Headers;
use Core\Controllers\Http\Psr\RequestBody;
use Core\Controllers\Http\Psr\UploadedFile;
use Core\Controllers\Http\Psr\Request;
use Core\Controllers\Http\Psr\Response;

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
		$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
		$uri = Uri::createFromString($escaped_url);


		$headers = Headers::createFromGlobals();
		$body = new RequestBody();
		$uploadedFiles = UploadedFile::createFromGlobals();
		$request = new Request($_SERVER['REQUEST_METHOD'], $uri, $headers, array(), $body, $uploadedFiles);
		$body = $request->getParams();
		$string = "has escrito <b>mola mucho</b>";
		$response = new Response();
		$response->write($string);




		/*$request = Request::getInstance();

		$router = new Router($this->routerCollection, $request);

		GFEventController::dispatch("Router.beforeMatch", null);
		$router->matchRequest();

		GFEventController::dispatch("Router.beforeExecute", null);
		$request->executeRequest();

		GFEventController::dispatch("Router.beforeSendResponse", null);
		$request->sendResponse();*/
		exit();


	}


	protected function loadModules($modules) {

		foreach ($modules as $loader) {
			new $loader($this->routerCollection);
		}
	}


}


















