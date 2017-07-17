<?php


use Core\Controllers\Router\RouteCollection;
use Core\Controllers\Router\RouteModel;
use Core\Controllers\Http\Request;

require_once __DIR__ .'/Core/Configs/Constants.php';
require_once 'GFStarter.php';
require_once 'GFAutoload.php';

if(file_exists( __DIR__ .'/Core/Vendors/autoload.php'))
	require_once __DIR__ .'/Core/Vendors/autoload.php';

setShowError(true);

$routerCollection = RouteCollection::getInstance();
attachCustomRoutes($routerCollection);
$modules = array();



/*
 * Add your modules here
 */
$modules[] = "Modules\GFStarterKit\Bootstrap";
$modules[] = "Modules\GFFileManager\Bootstrap";



$gfStarter = new GFStarter($routerCollection);

$gfStarter->initModules($modules);

$gfStarter->start();


function attachCustomRoutes(RouteCollection &$routerCollection) {


	$config = array();
	$config["name"] = "";
	$config["checkCSRF"] = false;

	$config["targetClass"] = "Modules\GFStarterKit\ViewsLogic\Pages\PAGAssignGenerator";
	$route = RouteModel::withConfig("/generador", $config);
	$routerCollection->attachRoute($route);

	$route = RouteModel::withFunction("/func", function() {
		$request = Request::getInstance();
		$request->setResponseBody("<b>It Works!</b>");
	});
	$routerCollection->attachRoute($route);
}



function setShowError($showError) {
	if ($showError) {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
}