<?php

date_default_timezone_set("Europe/Madrid");

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}


//MYSQL DATABASE
define("MYSQL_HOST", 'localhost');
define("DB_NAME", 'golgo_framework');
define("DB_USER", 'root');
define("DB_PASS", 'AAAAA');
define("DB_PORT", '3306');
define("DB_DRIVER", 'pdo_mysql');


//SESSION OPTIONS
define("SESSION_LENGTH", 3600); //30 MINS

define("LOCALIZATION_ENABLED", true);
define("DEFAULT_LOCALIZATION", "es");
define("GF_EVENTS_ENABLED", true);
define("JWT_SINGLE_SESSIONS_ONLY", true );

const GF_GLOBAL_SESSION = "gf_session";
const GF_DEFAULT_SESSION = "gf_default";




//HOST CONFIGURATION
define("DOMAIN_HOST","localhost");
define("DOMAIN_PATH","GolgoFramework");


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

//SECURITY

define("CSRF_ENABLED", true);
