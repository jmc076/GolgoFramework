<?php
namespace Modules\GFStarterKit\Controllers;



use JMS\Serializer\SerializerBuilder;

class SerializerController {

	private static $instance;


	/**
	 * The instance for serializing objects
	 * @return array
	 */
	public static function get() {
		if(!self::$instance) {

		$serializer = SerializerBuilder::create()
		->setCacheDir(ROOT_PATH . '/Modules/GFStarterKit/cache')
		->setDebug(true)
		->build();
		self::$instance = $serializer;
		}
		return self::$instance;
	}


	public static function serializeJSON($obj) {
		return self::get()->serialize($obj, 'json');

	}

	public static function deserialize($jsonData, $class) {
	   return self::get()->deserialize($jsonData, get_class($class), 'json');
	}


}