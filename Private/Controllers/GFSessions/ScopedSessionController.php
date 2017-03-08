<?php
namespace Controllers\GFSessions;


class ScopedSessionController implements ScopedSessionInterface{
	
	private static $extendedMethods = array();
	
	private static $extendedClasses = array();

	use ScopedSessionTrait;
	
	public function __call($method, $args) {
		if(array_key_exists($method, self::$extendedMethods)) {
			$className = self::$extendedMethods[$method];
			$obj = new $className;
			call_user_func_array(array($obj, $method), $args);
		}
		
	}
	
	public static function addExtendedClass($className) {
		self::$extendedClasses[] = $className;
	}
	public static function loadExtendedClass($className) {
		$class = new $className($this);
		return $class;
	}

	public static function addExtendedMethod($methodName, $className) {
		self::$extendedMethods[$methodName] = $className; 
	}
	
	public static function extendedMethodExist($methodName) {
		return array_key_exists($methodName, self::$extendedMethods);
	}
	
	public static function removeExtendedMethod($methodName) {
		unset(self::$extendedMethods[$methodName]);
	}
}
