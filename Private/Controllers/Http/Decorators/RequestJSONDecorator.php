<?php

namespace Controllers\Http\Decorators;


use Controllers\Http\Request;
use Helpers\HelperUtils;

/**
 * @author Diego
 *
 */
class RequestJSONDecorator {

    private $request;

	function __construct(Request $req) {
		$this->request = $req;
	}

	public function sendJSONResponse() {
		$result = array();
		$result["result"] = $this->request->getResponseBody();

		$result = HelperUtils::convertArrayKeysToUtf8($result);
		$this->request->setHeader("Content-Type", "application/json");
		$this->request->setResponseBody(json_encode($result));
	}

}