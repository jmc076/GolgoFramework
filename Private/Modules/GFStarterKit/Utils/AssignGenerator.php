<?php
namespace Modules\GFStarterKit\Utils;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Annotations\AnnotationReader;

class AssignGenerator {


	public static function generarAsignacion($entity) {
		$condicion = 'if(isset($dataArray["{{property}}"]) && $dataArray["{{property}}"] != "") {<br>';
		$condicion .= '<span style="margin-left:20px;">$model->{{method}}($dataArray["{{property}}"]);</span><br>}<br><br>';

		$objCondicion = 'if(isset($dataArray["{{property}}"]) && $dataArray["{{property}}"] != "") {<br>';
		$objCondicion .= '<span style="margin-left:20px;"> $pModel = new {{entity}}();</span><br>';
		$objCondicion .= '<span style="margin-left:20px;"> $pModel = $pModel->loadById($this->em, $dataArray["{{property}}"]);</span><br>';
		$objCondicion .= '<span style="margin-left:20px;"> $model->{{method}}($pModel);<span><br>}<br><br>';

		$booleanElse = 'if(isset($dataArray["{{property}}"])) {<br>';
		$booleanElse .= '<span style="margin-left:20px;">$model->{{method}}(1);</span><br>}';
		$booleanElse .= ' else { <br><span style="margin-left:20px;">$model->{{method}}(0);</span><br>}<br><br>';

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
		$output = "";
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
					$newAssign = str_replace("{{method}}",$method_name, $booleanElse);
					$newAssign = str_replace("{{property}}",$prop->name, $newAssign);
					$output .= $newAssign;
				} else {
					$newAssign = str_replace("{{method}}",$method_name, $condicion);
					$newAssign = str_replace("{{property}}",$prop->name, $newAssign);
					$output .= $newAssign;
				continue;
				}
		    } else {
		    	$newAssign = str_replace("{{method}}",$method_name, $objCondicion);
		    	$newAssign = str_replace("{{property}}",$prop->name, $newAssign);
		    	$newAssign = str_replace("{{entity}}",$docInfos[0]->targetEntity,$newAssign);
		    	$output .= $newAssign;
		    	continue;
		    }

		}
		return $output;
	}

	public static function generateForm($entity, $design = array(), $extras) {
		$inputs = array();


		if(!is_object($entity)) {
			print_r("First param must be an object"); die(); //TODO: Diego pre
		}
		$clazzName = get_class($entity);

		$reflectionClass = new \ReflectionClass($clazzName);

		$smartyEditClass = strtolower(explode("\\", $clazzName)[1]);

		// Then grap the class properites
		$clazzProps = $reflectionClass->getProperties();

		if (is_a($entity, 'Doctrine\ORM\Proxy\Proxy')){
			$parent = $reflectionClass->getParentClass();
			$clazzName = $parent->getName();
			$clazzProps = $parent->getProperties();
		}
		// A new array to hold things for us
		$docReader = new AnnotationReader();
		$count = 1;
		foreach ($clazzProps as $prop){
			$method_name = 'set' . ucfirst($prop->name) ;
			// And check to see that it exists for this object
			if (! method_exists($entity, $method_name)){
				continue;
			}

			$docInfos = $docReader->getPropertyAnnotations($reflectionClass->getProperty($prop->name));
			$isObject =  isset($docInfos[0]->targetEntity);
			if(!$isObject) {
					if($docInfos[0]->type == 'boolean') {
						$inputs[$count]["type"] = "checkbox";
						$inputs[$count]["smartyEdit"] = '{if isset($'.$smartyEditClass.') && isset($'.$smartyEditClass.'.'.$prop->name.') && $'.$smartyEditClass.'.'.$prop->name.' == 1}checked{/if}';
					} else if($docInfos[0]->type == 'string' || $docInfos[0]->type == 'text' ) {
						$inputs[$count]["type"] = "text";
						$inputs[$count]["smartyEdit"] = 'value="{if isset($'.$smartyEditClass.') && isset($'.$smartyEditClass.'.'.$prop->name.')}{$'.$smartyEditClass.'.'.$prop->name.'|utf8_decode}{/if}"';

					} else if($docInfos[0]->type == 'date' || $docInfos[0]->type == 'datetime' ) {
						$inputs[$count]["type"] = "date";
						$inputs[$count]["smartyEdit"] = 'value="{if isset($'.$smartyEditClass.') && isset($'.$smartyEditClass.'.'.$prop->name.')}{$'.$smartyEditClass.'.'.$prop->name.'|date_format:\'%d-%m-%Y\'}{/if}"';
					} else {
						$inputs[$count]["type"] = 'number';
						$inputs[$count]["smartyEdit"] = 'value="{if isset($'.$smartyEditClass.') && isset($'.$smartyEditClass.'.'.$prop->name.')}{$'.$smartyEditClass.'.'.$prop->name.'}{/if}"';
					}
					$inputs[$count]["name"] = $prop->name;
					$inputs[$count]["id"] = 'id_' . $prop->name;
					$count++;
					continue;
			} else {
				$inputs[$count]["type"] = "select";
				$selectOptions = '&#09;&#09;&#09;&#09;{foreach from=$'.$prop->name.' item=elem}'. "<br>";
				$selectOptions .= '&#09;&#09;&#09;&#09;&#09;&lt;option value="">Seleccione&lt;/option><br>&#09;&#09;&#09;&#09;&#09;&lt;option value="{$elem.id}"';
				$selectOptions .= ' {if isset($'.$smartyEditClass.') && isset($'.$smartyEditClass.'.'.$prop->name.') && $'.$smartyEditClass.'.'.$prop->name.'.id eq $elem.id}selected{/if}';
				$selectOptions .= '>{$elem.nombre}&lt;/option><br>&#09;&#09;&#09;&#09;{/foreach}<br>';

				$inputs[$count]["selectOptions"] = $selectOptions;
				$inputs[$count]["name"] = $prop->name;
				$inputs[$count]["id"] = 'id_' . $prop->name;
				$count++;
				continue;
			}


		}




		$output = '';
		$countFields = count($inputs);

		$countColumns = 3;
		if(isset($design["columnCount"])) $countColumns = $design["columnCount"];

		$numberRows = ceil($countFields / $countColumns);


		$rowClass = 'row';
		if(isset($design["rowClass"])) $rowClass = $design["rowClass"];

		$columnClass = 'col-xs-12 col-sm-6 col-md-4 col-lg-4';
		if(count($inputs) < $countColumns) {
			$columnClass = 'col-xs-12 col-sm-6 col-md-6 col-lg-6';
		}
		if(isset($design["columnClass"]))  $columnClass = $design["columnClass"];

		$formGroupClass = 'form-group';
		if(isset($design["formGroupClass"])) $formGroupClass = $design["formGroupClass"];

		$extraDivClass = 'fg-line';
		if(isset($design["extraDivClass"])) $extraDivClass  = $design["extraDivClass"];

		$labelClass = '';
		if(isset($design["labelClass"])) $labelClass  = $design["labelClass"];

		$inputClass = 'form-control';
		if(isset($design["inputClass"])) $labelClass  = $inputClass["inputClass"];

		for ($i = 1; $i <= $countFields; $i++) {

			$output .= '&lt;div class="' . $rowClass . '">' . "<br>";
		//	if($i == 1 || $i % $countColumns == 0)
				$output .= '&#09;&lt;div class="' . $columnClass . '">' . "<br>";
					$output .= '&#09;&#09;&lt;div class="' . $formGroupClass . '">' . "<br>";
						if(in_array("hasFormGroupExtraDiv",$extras)) {
							$output .= '&#09;&#09;&#09;&lt;div class="' . $extraDivClass . '">' . "<br>";
						}
							if(in_array("hasLabel",$extras)) {
								$output .= '&#09;&#09;&#09;&lt;label class="' . $labelClass . '" for="' . $inputs[$i]["id"] . '">' . $inputs[$i]["name"] . '&lt;/label>' . "<br>";
							}
							if($inputs[$i]["type"] == "select") {
								$output .= 	'&#09;&#09;&#09;&lt;select class="' . $inputClass .
								'" name="' . $inputs[$i]["name"] .
								'" id="' . $inputs[$i]["id"].'">'. "<br>";
								$output .= $inputs[$i]["selectOptions"];
								$output .= '&#09;&#09;&#09;&lt;/select>' . "<br>";
							} else {
								$output .= 	'&#09;&#09;&#09;&lt;input type="' . $inputs[$i]["type"] .
								'" class="' . $inputClass .
								'" name="' . $inputs[$i]["name"] .
								'" id="' . $inputs[$i]["id"] .
								'" '. $inputs[$i]["smartyEdit"] . ' >' . "<br>";
							}

						if(in_array("hasFormGroupExtraDiv",$extras)) {
							$output .= '&#09;&#09;&#09;&lt;/div>' . "<br>";
						}
					$output .= '&#09;&#09;&lt;/div>' . "<br>";
				//if($i == 1 || $i % $countColumns == 0)
				$output .= '&#09;&lt;/div>' . "<br>";
			$output .= '&lt;/div>' . "<br>";
		}

		return $output;

	}

}
