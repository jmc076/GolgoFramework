<?php

date_default_timezone_set("Europe/Madrid");

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
define("ROOT_PATH", $_SERVER['DOCUMENT_ROOT'] . DS );

//MYSQL DATABASE
define("MYSQL_HOST", 'DB_HOST');
define("DB_NAME", 'DB_NAME');
define("DB_USER", 'DB_USER');
define("DB_PASS", 'DB_PASS');
define("DB_PORT", '3306');
define("DB_DRIVER", 'pdo_mysql');


//SESSION OPTIONS
define("SESSION_LENGTH", 3600); //30 MINS
define("SESSION_ANONYM","BaseEntities\SessionBaseUser");

define("NEED_LOCALIZATION", true);
define("DEFAULT_LOCALIZATION", "es");
define("EVENTS_SYSTEM_ENABLED", true);



//HOST CONFIGURATION
define("BASE_PATH","localhost/GolgoFramework");


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