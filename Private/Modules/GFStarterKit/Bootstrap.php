<?php
namespace Modules\GFStarterKit;

require_once 'vendor/autoload.php';

use Controllers\Router\RouteCollection;
use Controllers\Router\RouteModel;
use Controllers\GFEvents\GFEventController;


define('GF_SMARTY_TEMPLATE_FOLDER', 'Private/Modules/GFStarterKit/Views/tpls');
define("TABLE_USERS", "gf_users");


class Bootstrap {


	function __construct(RouteCollection $routerCollection) {

		GFEntityManager::getEntityManager();

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

		$config["targetClass"] = $baseNamespace."\ViewsLogic\Pages\_Public\PAGPublicAdminLogin";
		$route = new RouteModel("acceso", $config);
		$routerCollection->attachRoute($route);

		$route = new RouteModel("/", $config);
		$routerCollection->attachRoute($route);


	}

}
