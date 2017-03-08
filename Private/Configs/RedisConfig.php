<?php

define('DB_REDIS_SCHEME', 'tcp');
define('DB_REDIS_HOST',   '127.0.0.1');
define('DB_REDIST_PORT',  '6379');

define('PREDIS_VERSION', 'Predis-1.1');
define('PREDIS_DIR', ROOT_PATH . '/Private/Vendors/' . PREDIS_VERSION . '/src/');

require PREDIS_DIR . 'Autoloader.php';
Predis\Autoloader::register();
