<?php
namespace Modules\GFStarterKit;

use Modules;
use Controllers\Router\RouteCollection;
use Modules\UserManagement\Controllers\PermissionsController;
use Controllers\Events\EventController;
use Controllers\Http\Request;
use Controllers\Router\RouteModel;


define('SMARTY_TEMPLATE_MODULES_FOLDER', 'Private/Modules/Views/tpls');
define("TABLE_USERS", "gf_users");


class Bootstrap {


	function __construct(RouteCollection $routerCollection) {
		$this->setRoutes($routerCollection);
		$this->startEventListeners();
	}

	private function startEventListeners() {
		$callback = function($params) { };
		EventController::listen("Router.dispatchWithMatch", $callback);
	}

	private function setRoutes(RouteCollection $routerCollection) {

		$baseNamespace = "Modules\GFStarter";


		$config = array();
		$config["name"] = "";
		$config["checkCSRF"] = false;

		$config["targetClass"] = $baseNamespace."\ViewsLogic\Pages\_Public\PAGPublicAdminLogin";
		$route = new RouteModel("acceso", $config);
		$routerCollection->attachRoute($route);

		$route = new RouteModel("/", $config);
		$routerCollection->attachRoute($route);


	}

}
