<?php
namespace Modules\GFStarterKit;


use Doctrine\Common\Cache\ArrayCache;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

class GFSKEntityManager
{
	private static $em;

	public static function getEntityManager($new = false)
	{
		if(!self::$em || self::$em == null) {
			//(array $paths, $isDevMode = false, $proxyDir = null, Cache $cache = null, $useSimpleAnnotationReader = true)

			$entityPath = array();
			$entityPath[] = __DIR__ . '/Entities';
			$proxyDir = __DIR__ . '/Proxies';
			$isDevMode = true;
			$cache = new ArrayCache();
			$useSimpleAnnotationReader = false;
			$config = Setup::createAnnotationMetadataConfiguration($entityPath, $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

			$connectionOptions = array(
					'driver' => DB_DRIVER,
					//'path' => 'database.mysql',
					'host' => MYSQL_HOST,
					'dbname' => DB_NAME,
					'user' => DB_USER,
					'password' => DB_PASS,
					'port' => DB_PORT
			);

			$conn = DriverManager::getConnection(array(
					'wrapperClass' => 'Doctrine\DBAL\Connections\MasterSlaveConnection',
					'driver' => $connectionOptions['driver'],
					'master' => array(
							'user' => $connectionOptions['user'],
							'password' => $connectionOptions['password'],
							'host' => $connectionOptions['host'],
							'port' => $connectionOptions['port'],
							'dbname' => $connectionOptions['dbname']
					),
					'slaves' => array(
							array(
									'user' => $connectionOptions['user'],
									'password' => $connectionOptions['password'],
									'host' => $connectionOptions['host'],
									'port' => $connectionOptions['port'],
									'dbname' => $connectionOptions['dbname']
							)
					)
			));
			self::$em = EntityManager::create($conn, $config);
		}
		return self::$em;
	}
}