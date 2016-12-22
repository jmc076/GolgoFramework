<?php
class GolgoDoctrineEntityManager
{
	private static $em;

	public static function getEntityManager($new = false)
	{
		if(!self::$em || self::$em == null) {
			$config = new Doctrine\ORM\Configuration();
			$driverImpl = $config->newDefaultAnnotationDriver(array(
					__DIR__ . '/Private/Entities'
			),false);
				
			$config->setMetadataDriverImpl($driverImpl);
			$config->setMetadataCacheImpl(new Doctrine\Common\Cache\ArrayCache);
			$config->setProxyDir(__DIR__ . '/Private/Proxies');
			$config->setProxyNamespace('Proxies');
				
			$config->setAutoGenerateProxyClasses(true);
				
			$connectionOptions = array(
					'driver' => DB_DRIVER,
					//'path' => 'database.mysql',
					'host' => MYSQL_HOST,
					'dbname' => DB_NAME,
					'user' => DB_USER,
					'password' => DB_PASS,
					'port' => DB_PORT
			);
				
			$conn = Doctrine\DBAL\DriverManager::getConnection(array(
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
			self::$em = Doctrine\ORM\EntityManager::create($conn, $config);
		}
		return self::$em;
	}
}