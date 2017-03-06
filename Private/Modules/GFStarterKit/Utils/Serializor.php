<?php
namespace Modules\GFStarterKit\Utils;

class Serializor {

	/**
	 * Converts the Doctrine Entity into a JSON Representation
	 *
	 * @param object $object The Object (Typically a Doctrine Entity) to convert to an array
	 * @param integer $depth The Depth of the object graph to pursue
	 * @param array $whitelist List of entity=>array(parameters) to convert
	 * @param array $blacklist List of entity=>array(parameters) to skip
	 * @return string
	 */
	public static function json_encode($object, $depth=1, $whitelist=array(), $blacklist=array()){
		return json_encode(Serializor::toArray($object, $depth, $whitelist, $blacklist));
	}

	/**
	 * Serializes our Doctrine Entities
	 *
	 * This is the primary entry point, because it assists with handling collections
	 * as the primary Object
	 *
	 * @param object $object The Object (Typically a Doctrine Entity) to convert to an array
	 * @param integer $depth The Depth of the object graph to pursue
	 * @param array $whitelist List of entity=>array(parameters) to convert
	 * @param array $blacklist List of entity=>array(parameters) to skip
	 * @return NULL|Array
	 *
	 */
	public static function toArray($object, $depth = 1,$whitelist=array(), $blacklist=array(), $propBlackList = array()){

		// If we drop below depth 0, just return NULL
		if ($depth < 0){
			return NULL;
		}

		// If this is an array, we need to loop through the values
		if (is_array($object) || is_a($object, 'Doctrine\ORM\PersistentCollection')){
			// Somthing to Hold Return Values
			$anArray = array();

			// The Loop
			foreach ($object as $value){
				// Store the results
				$anArray[] = Serializor::arrayizor($value, $depth, $whitelist, $blacklist,$propBlackList);
			}
			// Return it
			return $anArray;
		}else{
			// Just return it
			return Serializor::arrayizor($object, $depth, $whitelist, $blacklist,$propBlackList);
		}
	}

	/**
	 * This does all the heavy lifting of actually converting to an array
	 *
	 * @param object $object The Object (Typically a Doctrine Entity) to convert to an array
	 * @param integer $depth The Depth of the object graph to pursue
	 * @param array $whitelist List of entity=>array(parameters) to convert
	 * @param array $blacklist List of entity=>array(parameters) to skip
	 * @return NULL|Array
	 */
	private static function arrayizor($anObject, $depth, $whitelist=array(), $blacklist=array(), $propBlackList = array()){
		// Determine the next depth to use
		$nextDepth = $depth - 1;

		// Lets get our Class Name
		// @TODO: Making some assumptions that only objects get passed in, need error checking
		if(!is_object($anObject)) {
			return "";
		}
		$clazzName = get_class($anObject);

		// Now get our reflection class for this class name
		$reflectionClass = new \ReflectionClass($clazzName);

		// Then grap the class properites
		$clazzProps = $reflectionClass->getProperties();

		if (is_a($anObject, 'Doctrine\ORM\Proxy\Proxy')){
			$parent = $reflectionClass->getParentClass();
			$clazzName = $parent->getName();
			$clazzProps = $parent->getProperties();
		}
		// A new array to hold things for us
		$anArray = array();

		// Lets loop through those class properties now
		foreach ($clazzProps as $prop){
			if(!in_array($prop->name, $propBlackList)) {

				// If a Whitelist exists
				if (@count($whitelist[$clazzName]) > 0){
					// And this class property is not in it
					if (! @in_array($prop->name, $whitelist[$clazzName])){
						// lets skip it.
						continue;
					}
				// Otherwise, if a blacklist exists
				}elseif (@count($blacklist[$clazzName] > 0)){
					// And this class property is in it
					if (@in_array($prop->name, $blacklist[$clazzName])){
						// lets skip it.
						continue;
					}
				}

				// We know the property, lets craft a getProperty method
				$method_name = 'get' . ucfirst($prop->name) ;
				// And check to see that it exists for this object
				if (! method_exists($anObject, $method_name)){
					continue;
				}
				// It did, so lets call it!
				try {
					$aValue = $anObject->$method_name();
				} catch (\Doctrine\ORM\EntityNotFoundException $e) {
					$aValue = "";
				}


				// If it is an object, we need to handle that
				if (is_object($aValue)){
					// If it is a datetime, lets make it a string
					if (get_class($aValue) === 'DateTime'){
						$anArray[$prop->name] = $aValue->format('d-m-Y H:i:s');

					// If it is a Doctrine Collection, we need to loop through it
					}elseif(get_class($aValue) ==='Doctrine\ORM\PersistentCollection'){
						$collect = array();
						foreach ($aValue as $val){
							$collect[] = Serializor::toArray($val, $nextDepth, $whitelist, $blacklist, $propBlackList);
						}
						$anArray[$prop->name] = $collect;

					// Otherwise, we can simply make it an array
					}else{
						$anArray[$prop->name] = Serializor::toArray($aValue, $nextDepth, $whitelist, $blacklist, $propBlackList);
					}
				// Otherwise, we just use the base value
				}else{
					if($aValue == null) {
						$anArray[$prop->name] = "";
					} else $anArray[$prop->name] = Serializor::utf8_converter($aValue);
				}
			} else {
				$method_name = 'get' . ucfirst($prop->name) ;
				// And check to see that it exists for this object
				if (! method_exists($anObject, $method_name)){
					continue;
				}
				// It did, so lets call it!
				$aValue = $anObject->$method_name();
				if (is_array($aValue) || is_a($aValue, 'Doctrine\ORM\PersistentCollection')){
					$anArray[$prop->name] = count($aValue);
				}
			}
		}
		// All done, send it back!
		return $anArray;
	}
	public static function utf8_converter($value)
	{

			if(gettype($value) == "string" && !mb_detect_encoding($value, 'utf-8', true)){
				$value = utf8_encode($value);
			}

			return $value;
	}


}
