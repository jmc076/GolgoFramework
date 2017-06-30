<?php
namespace Controllers;

class CacheController {

	protected $redisClient;
	private static $instance;


	private function __construct(){}

	public static function get() {
		if ( !self::$instance instanceof self) {
			self::$instance = new self;
			self::$instancia->setRedisClient(RedisCacheController::getRedisClient());
		}
		return self::$instance;
	}

	protected function setRedisClient($client) {
		$this->redisClient = $client;
	}

	public  function getFromCache($key) {
		if(static::$redisClient->exists($key)) {
			return json_decode(static::$redisClient->get($key));
		}
		return null;
	}

	public function saveToCache($key, $value) {
		try {
			static::$redisClient->setex($key, 100 ,json_encode($value));
		} catch (Exception $e) {
			return false;
		}

		return true;
	}


}