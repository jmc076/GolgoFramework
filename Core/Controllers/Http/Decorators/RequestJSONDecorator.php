<?php

namespace Core\Controllers\Http\Decorators;



use Core\Controllers\Http\Request;
use Core\Helpers\Utils;

/**
 * @author Diego
 *
 */
class RequestJSONDecorator {

    private $request;

	function __construct(Request $req) {
		$this->request = $req;
	}

	public function setJSONResponse() {
		$result = array();
		$result["result"] = $this->request->getResponseBody();

		$result = Utils::convertArrayKeysToUtf8($result);
		$this->request->setHeader("Content-Type", "application/json");
		$this->request->setResponseBody(json_encode($result));
	}

}