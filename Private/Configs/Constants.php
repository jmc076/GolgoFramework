<?php

date_default_timezone_set("Europe/Madrid");

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
define("ROOT_PATH", $_SERVER['DOCUMENT_ROOT'] . DS );

define("MYSQL_HOST", 'AAAAAAA');
define("DB_NAME", 'AAAAAAA');
define("DB_USER", 'AAAAAAA');
define("DB_PASS", 'AAAAAAA');
define("DB_PORT", '3306');
define("DB_DRIVER", 'pdo_mysql');

define("SESSION_LENGTH", 3600); //30 MINS
define("SESSION_NAME", "AAAAAAA");

define("NEED_LOCALIZATION", true);
define("DEFAULT_LOCALIZATION", "es");
define("EVENTS_SYSTEM_ENABLED", true);

define("SESSION_ANONYM","BaseEntities\SessionBaseUser");

define("SMTP_HOST","");
define("SMTP_USER","");
define("SMTP_PASS","");
define("SMTP_FROM","");
define("SMTP_FROM_NAME","");


define('SMARTY_TEMPLATE_FOLDER', 'Private/Views/tpls');
define('SMARTY_TEMPLATE_MODULES_FOLDER', 'Private/Modules');

//LOCATION CONFIG CONSTANTS

define("BASE_PATH","www.hexenbytes.com/tivoli");
define("TABLE_USERS", "um_users");


define("TPLS_GLOBAL_DIRECTORY", ROOT_PATH . 'Private/Views/tpls');
define("TPLS_COMMONS", ROOT_PATH . 'Private/Views/tpls/private/common');

define("REDIS_CACHE_ENABLED", false);
if(REDIS_CACHE_ENABLED)
    include '/RedisConfig.php';
