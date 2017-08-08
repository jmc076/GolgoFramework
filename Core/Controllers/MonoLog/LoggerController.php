<?php
namespace Core\Controllers\MonoLog;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class LoggerController {

	private static $instance;
	private $logger;

	private function __construct(){
		$this->logger = new Logger('GF_LOGGER');
		$dsn = 'mysql:dbname='.DB_NAME.';host='. MYSQL_HOST;
		$this->logger->pushHandler(new StreamHandler(dirname(dirname(__DIR__)) . DS . 'Logs/gf_logs.log', Logger::INFO));
		$this->logger->pushHandler(new MySQLHandler(new \PDO($dsn, DB_USER, DB_PASS)));
	}

	public static function get() {
		if ( !self::$instance instanceof self) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public function logDebug($string, array $context = array()) {
		$this->logger->debug($string, $context);
	}
	public function logInfo($string, array $context = array()) {
		$this->logger->info($string, $context);
	}
	public function logNotice($string) {
		$this->logger->notice($string);
	}
	public function logWarning($string) {
		$this->logger->warning($string);
	}
	public function logError($string) {
		$this->logger->error($string);
	}
	public function logCritical($string) {
		$this->logger->critical($string);
	}
	public function logAlert($string) {
		$this->logger->alert($string);
	}
	public function logEmergency($string) {
		$this->logger->emergency($string);
	}





}