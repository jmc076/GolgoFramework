<?php
namespace Modules\GFStarterKit\Utils;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Annotations\AnnotationReader;

class AssignGenerator {


	public static function generarAsignacion(&$entity, $dataArray) {
		if(!is_object($entity)) {
			print_r("First param must be an object"); die(); //TODO: Diego pre
		}
		$clazzName = get_class($entity);

		$reflectionClass = new \ReflectionClass($clazzName);

		// Then grap the class properites
		$clazzProps = $reflectionClass->getProperties();

		if (is_a($entity, 'Doctrine\ORM\Proxy\Proxy')){
			$parent = $reflectionClass->getParentClass();
			$clazzName = $parent->getName();
			$clazzProps = $parent->getProperties();
		}
		// A new array to hold things for us
		$docReader = new AnnotationReader();
		foreach ($clazzProps as $prop){


			// We know the property, lets craft a getProperty method
			$method_name = 'set' . ucfirst($prop->name) ;
			// And check to see that it exists for this object
			if (! method_exists($entity, $method_name)){
				continue;
			}

			$docInfos = $docReader->getPropertyAnnotations($reflectionClass->getProperty($prop->name));
			$isObject =  isset($docInfos[0]->targetEntity);
			if(!$isObject) {
				if($docInfos[0]->type == 'boolean') {
					if(isset($dataArray[$prop->name])) {
						call_user_func_array(array($entity, $method_name), array(1));
					} else {
						call_user_func_array(array($entity, $method_name), array(0));
					}
					continue;
				} else {
					if(isset($dataArray[$prop->name]) && $dataArray[$prop->name] != "") {
						call_user_func_array(array($entity, $method_name), array($dataArray[$prop->name]));
					}
					continue;
				}
		    } else {

		    	if(isset($dataArray[$prop->name]) && $dataArray[$prop->name] != "") {
		    		$targetEntity = new $docInfos[0]->targetEntity;
		    		$targetEntity = $targetEntity->loadById($dataArray[$prop->name]);
		    		call_user_func_array(array($entity, $method_name), array($targetEntity));
		    	}
		    	continue;
		    }

		}
	}



}
