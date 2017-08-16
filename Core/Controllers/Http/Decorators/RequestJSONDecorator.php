<?php

namespace Core\Controllers\Http\Decorators;



use Core\Helpers\Utils;
use Core\Controllers\Http\Psr\Request;

/**
 * @author Diego
 *
 */
class RequestJSONDecorator {

    private $request;

	function __construct(Request &$request) {
		$this->request = $request;
	}

	public function setJSONResponse() {
		$result = array();
		$result["result"] = $this->request->getResponse()->getResponseBody();
		$result = Utils::convertArrayKeysToUtf8($result);
		$this->request->getResponse()->putHeaderValue("Content-Type", "application/json");
		$this->request->getResponse()->setResponseBody(json_encode($result));
	}

}