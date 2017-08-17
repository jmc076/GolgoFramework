<?php
namespace Modules\GFFileManager;


use Modules\GFStarterKit\ViewsLogic\Pages\_Private\dashboard\PAGPrivateAdministracionBase;
use Core\Controllers\Router\RouteCollection;
use Core\Controllers\Router\RouteModel;

define("FILES_FOLDER", dirname(__FILE__) .DS. "Files");

class Bootstrap {

	public static $baseNamespace = 'Modules'.DS.'GFFileManager';

	function __construct(RouteCollection $routerCollection) {
		PAGPrivateAdministracionBase::addItemMenu(array("url"=>"/GolgoFramework/dashboard/filemanager", "title"=>"File Manager", "icon"=>"folder", "isActive"=> ""));
		$this->setRoutes($routerCollection);
	}


	private function setRoutes(RouteCollection $routerCollection) {



		$config = array();
		$config["name"] = "";
		$config["checkCSRF"] = false;

		/**
		 * PAGE ROUTES
		 */

		$config["targetClass"] = self::$baseNamespace."\ViewsLogic\PAGPrivateAdministracionFileManager";
		$route = RouteModel::withConfig("/dashboard/filemanager", $config);
		$routerCollection->attachRoute($route);

		$config["targetClass"] = self::$baseNamespace."\EntitiesLogic\FilesLogic";
		$route = RouteModel::withConfig("/Files", $config);
		$routerCollection->attachRoute($route);



		/**
		 * API ROUTES
		 */
		$config["targetClass"] = self::$baseNamespace."\EntitiesLogic\FilesLogic";
		$route = RouteModel::withConfig("/api/Files", $config);
		$routerCollection->attachRoute($route);



	}

}
