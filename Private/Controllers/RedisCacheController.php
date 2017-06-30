<?php
namespace Controllers;

/**
 * UNDER DEVELOPMENT
 * @author Diego Lopez Rivera <forgin50@gmail.com>
 * @version 0.0.1
 */
class RedisCacheController {

    private static $redisClient;


    public static function getRedisClient() {
        if(self::$redisClient == null) {
            self::$redisClient = new \Predis\Client(array (
                'scheme' => DB_REDIS_SCHEME,
                'host' => DB_REDIS_HOST,
                'port' => DB_REDIST_PORT
            ));
           return self::$redisClient;
        } else {
            return self::$redisClient;
        }

    }

}