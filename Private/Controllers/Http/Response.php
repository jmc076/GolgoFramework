<?php

namespace Controllers\Http;

use Controllers\ExceptionController;
use Helpers\HelperUtils;
use ViewsLogic\Pages\_Base\PAGPublic404;
use Controllers\Http\Interfaces\ResponseInterface;

/**
 * @author Diego
 *
 */
class Response extends HttpBase implements ResponseInterface {
	
	
	public function dispatchRequest(Request $request) {
		
		if(!$request->getHasMatch()) {
			$this->dispatchNoMatch($request);
			
		} else {
			$matchedRoute = $request->getMatchedRoute();
			$class = $matchedRoute->getTargetClass();
			
			if($request->getIsApi()) {
				
				if ($matchedRoute->getTargetClassMethod() != null) {
					call_user_func_array(array($class, $matchedRoute->getTargetClassMethod()), array($request,$this));
				} else {
					new $class($request,$this);
				}
				
		
			} else {
				
				if($matchedRoute->getTargetClassMethod() != null) {
					call_user_func_array(array($class, $matchedRoute->getTargetClassMethod()), array($request,$this));
					
				} else {
					new $class($request,$this);
					
				}
			}
		}
	}
	
	
	public function sendResponse() {
		$this->attachHeaders();
		if (is_null($this->getBody())) return;
	
		$contentLength = $this->getHeaderAsString('Content-Length');
		if ($contentLength !== null) {
			$output = fopen('php://output', 'wb');
			if (is_resource($this->getBody()) && get_resource_type($this->getBody()) == 'stream') {
				stream_copy_to_stream($this->getBody(), $output, $contentLength);
			} else {
				fwrite($output, $this->getBody(), $contentLength);
			}
		} else {
			file_put_contents('php://output', $this->getBody());
		}
		
		if (is_resource($this->getBody())) {
			fclose($this->getBody());
		}
		exit();
	}
	
	
	public function attachHeaders() {
		header('HTTP/' . $this->httpVersion . ' ' . $this->statusCode. ' ' . HelperUtils::$statusCodes[$this->statusCode]);
		foreach ($this->getAllHeadersAsArray() as $key => $value) {
		
			foreach ($value as $k => $v) {
				if ($k === 0) {
					header($key . ': ' . $v);
				} else {
					header($key . ': ' . $v, false);
				}
			}
		
		}
	}
	
	public function dispatchNoMatch(Request $request) {
		if($request->getIsApi()) {
			ExceptionController::routeNotFound();
		} else {
			new PAGPublic404($request,$this);
		}
	
	}
	
	
	
	
}