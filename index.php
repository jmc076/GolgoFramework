<?php

use Controllers\Router\RouteCollection;
use Controllers\Router\RouteModel;
require_once __DIR__ .'/Private/Configs/Constants.php';
require_once 'GFStarter.php';

function namespaceAutoloads($class) {
	$class_dir = ROOT_PATH . 'Private';
	$filename = $class_dir . DS . $class . '.php';
	$filename = str_replace('\\', DS, $filename);
	if (file_exists($filename)) {
		require $filename;
	} else {
		$class_dir = ROOT_PATH . 'Private\Vendors';
		$filename = $class_dir . DS . $class . '.php';
		$filename = str_replace('\\', DS, $filename);
		if (file_exists($filename)) {
			require $filename;
		} 
	}
	
}
spl_autoload_register('namespaceAutoloads');
setShowError(true);

$routerCollection = new RouteCollection();
attachCustomRoutes($routerCollection);

$starter = new GFStarter($routerCollection);
$starter->start();


function attachCustomRoutes(RouteCollection &$routerCollection) {
	$config = array();
	$config["name"] = "";
	$config["checkCSRF"] = false;
		
	$config["targetClass"] = "ViewsLogic\Pages\_Base\PAGAssignGenerator";
	$route = new RouteModel("/generador", $config);
	$routerCollection->attachRoute($route);
	
	$config["targetClass"] = "ViewsLogic\Pages\_Private\PAGPrivateGetFile";
	$route = new RouteModel("/getPrivateFiles", $config);
	$routerCollection->attachRoute($route);
}

function setShowError($showError) {
	if ($showError) {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
}