
<?php 

use Doctrine\ORM\Tools\EntityGenerator;
ini_set("display_errors", "On");
$libPath = __DIR__ . "/Private/"; // Set this to where you have doctrine2 installed
// autoloaders
require_once './Private/Doctrine/Common/ClassLoader.php';
include_once 'Private/Entities/BaseEntities/BasicModel.php';

$classLoader = new \Doctrine\Common\ClassLoader('Doctrine', $libPath);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Entities', __DIR__);
$classLoader->register();

$classLoader = new \Doctrine\Common\ClassLoader('Proxies', __DIR__);
$classLoader->register();

// config
$config = new \Doctrine\ORM\Configuration();
$config->setMetadataDriverImpl($config->newDefaultAnnotationDriver(__DIR__ . '/Entities'));
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache);
$config->setProxyDir(__DIR__ . '/Proxies');
$config->setProxyNamespace('Proxies');


$connectionParams = array(
		'driver' => 'pdo_mysql',
		'path' => 'database.mysql',
		'host' => 'localhost',
		'dbname' => 'tivoli',
		'user' => 'root',
		'password' => 'golgo2007',
		'port' => 3306
);

$em = \Doctrine\ORM\EntityManager::create($connectionParams, $config);

// custom datatypes (not mapped for reverse engineering)
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('set', 'string');
$em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

// fetch metadata
$driver = new \Doctrine\ORM\Mapping\Driver\DatabaseDriver(
    $em->getConnection()->getSchemaManager()
);
$em->getConfiguration()->setMetadataDriverImpl($driver);
$cmf = new \Doctrine\ORM\Tools\DisconnectedClassMetadataFactory($em);
$cmf->setEntityManager($em); 
$classes = $driver->getAllClassNames();
$metadata = $cmf->getAllMetadata(); 
$generator = new EntityGenerator();
$generator->setUpdateEntityIfExists(true);
$generator->setGenerateStubMethods(true);
$generator->setGenerateAnnotations(true);
$generator->setClassToExtend("Entities\BasicModel");
$generator->generate($metadata, __DIR__ . '/Private/Entities/newEntities');
print 'Done!';

?>