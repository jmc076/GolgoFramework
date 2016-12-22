<?php

namespace Controllers\Http\Interfaces;



use Controllers\Http\Request;

/**
 * @author Diego
 *
 */
interface ResponseInterface {
	
	
	public function dispatchRequest(Request $request);
	
	public function sendResponse();
	
	public function attachHeaders();
	
	public function dispatchNoMatch(Request $request);
	

	
}