<?php
function namespaceAutoloads($class) {
	if(file_exists($class . ".php"))
		require_once $class.".php";
	else {
		$class_dir = ROOT_PATH . '/Private';
		$filename = $class_dir . DS . $class . '.php';
		$filename = str_replace('\\', DS, $filename);
		if (file_exists($filename)) {
			require $filename;
		} else {
			$class_dir = ROOT_PATH . '/Private/Vendors';
			$filename = $class_dir . DS . $class . '.php';
			$filename = str_replace('\\', DS, $filename);
			if (file_exists($filename)) {
				require $filename;
			} else {
				$class_dir = ROOT_PATH . '/Modules';

				$dir = new DirectoryIterator($class_dir);
				foreach ($dir as $fileinfo) {
					if ($fileinfo->isDir() && !$fileinfo->isDot()) {
						if($fileinfo->getFilename() == "Entities") {
							$filename = $fileinfo->getPath() . DS . $class . '.php';
							$filename = str_replace('\\', DS, $filename);
							if (file_exists($filename)) {
								require $filename;
							}
						}
					}
				}


			}
		}
	}

}
spl_autoload_register('namespaceAutoloads');