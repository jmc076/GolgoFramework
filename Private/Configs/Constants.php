<?php

date_default_timezone_set("Europe/Madrid");

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
define("ROOT_PATH", $_SERVER['DOCUMENT_ROOT'] . dirname($_SERVER['PHP_SELF']) );

//MYSQL DATABASE
define("MYSQL_HOST", 'localhost');
define("DB_NAME", 'golgo_framework');
define("DB_USER", 'root');
define("DB_PASS", 'golgo2007');
define("DB_PORT", '3306');
define("DB_DRIVER", 'pdo_mysql');


//SESSION OPTIONS
define("SESSION_LENGTH", 3600); //30 MINS

define("NEED_LOCALIZATION", true);
define("DEFAULT_LOCALIZATION", "es");
define("GF_EVENTS_ENABLED", true);



//HOST CONFIGURATION
define("BASE_PATH","localhost/GolgoFramework");
define("BASE_PATH_DIRECTORY","GolgoFramework");


//REDIS CACHE
define("REDIS_CACHE_ENABLED", false);
if(REDIS_CACHE_ENABLED)
    include '/RedisConfig.php';

//SMTP CONFIGURATION
define("SMTP_HOST","");
define("SMTP_USER","");
define("SMTP_PASS","");
define("SMTP_FROM","");
define("SMTP_FROM_NAME","");