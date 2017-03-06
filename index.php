<?php

use Controllers\Router\RouteCollection;
use Controllers\Router\RouteModel;
require_once __DIR__ .'/Private/Configs/Constants.php';
require_once 'vendor/autoload.php';
require_once 'GFStarter.php';
require_once 'GFAutoload.php';

setShowError(true);

$routerCollection = RouteCollection::getInstance();
attachCustomRoutes($routerCollection);
$modules = array();
/*
 * Add your modules here
 */
$modules[] = "Modules\GFStarterKit\Bootstrap";


$gfStarter = new GFStarter($routerCollection);
$gfStarter->start($modules);


function attachCustomRoutes(RouteCollection &$routerCollection) {

}



function setShowError($showError) {
	if ($showError) {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
}