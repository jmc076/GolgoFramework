<?php
function namespaceAutoloads($class) {

	if(file_exists($class . ".php")) {
		require_once $class.".php";
	} else {
		$filename = ROOT_PATH . DS . $class . '.php';
		$filename = str_replace('\\', DS, $filename);
		if (file_exists($filename)) {
			require_once $filename;
		} else {
			return;
		}
	}
}
spl_autoload_register('namespaceAutoloads');