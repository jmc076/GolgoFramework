<?php

use Core\Controllers\Http\Psr\Response;
use Modules\GFStarterKit\ViewsLogic\Pages\PAGAssignGenerator;
define("ROOT_PATH", __DIR__);
setShowError(true);
require_once 'GFStarter.php';


$App = new GFStarter();

$App->init();

/**
 * Add your modules here
 */
$activeModules = array();
$activeModules[] = "Modules\GFStarterKit\Bootstrap";
$activeModules[] = "Modules\GFFileManager\Bootstrap";



/**
 * YOU CAN ATTACH ROUTES HERE
 */
$App->withRoute("all", "/generador", PAGAssignGenerator::class);
$App->withRoute("all", "/func", function() {
	Response::getResponseInstance()->writeToBody("<b>It Works!</b>");
});

$App->loadModules($activeModules);

$App->start();



function setShowError($showError) {
	if ($showError) {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
}
