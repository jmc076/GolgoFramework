<?php
namespace Modules\GFStarterKit;

require_once 'vendor/autoload.php';

use Controllers\Router\RouteCollection;
use Controllers\Router\RouteModel;
use Controllers\GFEvents\GFEventController;


define('GF_SMARTY_TEMPLATE_FOLDER', 'Private/Modules/GFStarterKit/Views/tpls');
define("GF_TABLE_USERS", "gf_users");


class Bootstrap {


	function __construct(RouteCollection $routerCollection) {

		GFDoctrineManager::getEntityManager();

		$this->setRoutes($routerCollection);
		$this->startEventListeners();
	}

	private function startEventListeners() {
		$callback = function($params) { };
		GFEventController::on("Router.dispatchWithMatch", $callback);
	}

	private function setRoutes(RouteCollection $routerCollection) {

		$baseNamespace = "Modules\GFStarterKit";


		$config = array();
		$config["name"] = "";
		$config["checkCSRF"] = false;

		/**
		 * PAGE ROUTES
		 */
		$config["targetClass"] = $baseNamespace."\ViewsLogic\Pages\_Public\PAGPublicAdminLogin";
		$route = new RouteModel("login", $config);
		$routerCollection->attachRoute($route);
		$route = new RouteModel("/acceso", $config);
		$routerCollection->attachRoute($route);
		$route = new RouteModel("/", $config);
		$routerCollection->attachRoute($route);

		/**
		 * API ROUTES
		 */
		$config["targetClass"] = $baseNamespace."\EntitiesLogic\UserManagementLogic\UserLogic";
		$route = new RouteModel("/api/Users", $config);
		$routerCollection->attachRoute($route);


	}

}
