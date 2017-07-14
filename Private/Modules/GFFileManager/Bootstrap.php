<?php
namespace Modules\GFFileManager;


use Controllers\Router\RouteCollection;
use Controllers\Router\RouteModel;
use Modules\GFStarterKit\ViewsLogic\Pages\_Private\dashboard\PAGPrivateAdministracionBase;


class Bootstrap {


	function __construct(RouteCollection $routerCollection) {
		PAGPrivateAdministracionBase::addItemMenu(array("url"=>"/GolgoFramework/dashboard/filemanager", "title"=>"File Manager", "icon"=>"folder", "isActive"=> ""));
		$this->setRoutes($routerCollection);
	}


	private function setRoutes(RouteCollection $routerCollection) {

		$baseNamespace = "Modules\GFFileManager";


		$config = array();
		$config["name"] = "";
		$config["checkCSRF"] = false;

		/**
		 * PAGE ROUTES
		 */

		$config["targetClass"] = $baseNamespace."\ViewsLogic\PAGPrivateAdministracionFileManager";
		$route = RouteModel::withConfig("/dashboard/filemanager", $config);
		$routerCollection->attachRoute($route);



		/**
		 * API ROUTES
		 */
		$config["targetClass"] = $baseNamespace."\EntitiesLogic\UserManagementLogic\BaseUserLogic";
		$route = RouteModel::withConfig("/api/Users", $config);
		$routerCollection->attachRoute($route);



	}

}
