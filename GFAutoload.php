<?php
function namespaceAutoloads($class) {
	$class_dir = ROOT_PATH . '/Private';
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