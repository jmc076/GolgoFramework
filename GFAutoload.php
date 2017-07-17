<?php
function namespaceAutoloads($class) {

	if(file_exists($class . ".php"))
		require_once $class.".php";
	return;
}
spl_autoload_register('namespaceAutoloads');