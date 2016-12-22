<?php

namespace Controllers\Http\Decorators;

use Controllers\Http\Response;
use Doctrine\Common\DoctrineHelper;

/**
 * @author Diego
 *
 */
class ResponseJSONDecorator extends Response {
	
	private $response;
	
	function __construct(Response $response) {
		$this->response = $response;
	}
	
	public function dispatchJSONResponse() {
		$result = array();
		$result["resources"] = $this->response->getBody();
		if ($totalRows = DoctrineHelper::stGetTotalRows()) {
			$result['iTotalDisplayRecords'] = $totalRows;
		}
		if ($limitedRows = DoctrineHelper::stGetLimitedRows()) {
			$result['iTotalRecords'] = $limitedRows;
		}
		
		$result = $this->convertArrayKeysToUtf8($result);
		
		$this->setHeader("Content-Type", "application/json");
		$this->setBody(json_encode($result));
		$this->sendResponse();
		exit();
	}
	
	function convertArrayKeysToUtf8(array $array) {
		$convertedArray = array();
		foreach($array as $key => $value) {
			if(!mb_check_encoding($key, 'UTF-8')) $key = utf8_encode($key);
			if(is_array($value)) $value = $this->convertArrayKeysToUtf8($value);
	
			$convertedArray[$key] = $value;
		}
		return $convertedArray;
	}
}