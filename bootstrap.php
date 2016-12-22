<?php

//INICIALIZADOR DE ORM DOCTRINE
$doctrineFolder = realpath(__DIR__ . '/Private/Vendors');
require $doctrineFolder . '/Doctrine/Common/ClassLoader.php';
require_once 'GolgoDoctrineEntityManager.php';

$classLoader = new Doctrine\Common\ClassLoader('Doctrine', $doctrineFolder . "/");
$classLoader->register();
$classLoader = new Doctrine\Common\ClassLoader('Doctrine\ORM', $doctrineFolder . '/ORM');
$classLoader->register();
$classLoader = new Doctrine\Common\ClassLoader('Doctrine\DBAL', $doctrineFolder . '/DBAL');
$classLoader->register();
$classLoader = new Doctrine\Common\ClassLoader('Doctrine\Common', $doctrineFolder . '/Common');
$classLoader->register();

$classLoader = new Doctrine\Common\ClassLoader('Entities', __DIR__ . '/Private');
$classLoader->register();

$classLoader = new Doctrine\Common\ClassLoader('BaseEntities', __DIR__ . '/Private/Entities');
$classLoader->register();

$classLoader = new Doctrine\Common\ClassLoader('Lists', __DIR__ . '/Private/Entities');
$classLoader->register();

$classLoader = new Doctrine\Common\ClassLoader('Proxies', $doctrineFolder . '/Proxies');
$classLoader->register();


global $em;
$em = GolgoDoctrineEntityManager::getEntityManager();






