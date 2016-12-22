<?php
/**
 * @author Diego
 * @version 1.0
 */

namespace Controllers\Http;


use Helpers\HelperUtils;
use Controllers\Router\Route;


class Request extends HttpBase {
	
	/**
	 * Array of (incoming) post parameters.
	 * @var array() $PostParams
	 */
	protected $PostParams = array();
	
	/**
	 * Array of (incoming) get parameters.
	 * @var array() $GetParams
	 */
	protected $GetParams = array();
	
	/**
	 * Array of url params, those defined in the Route class url filter (example/:paramKey).
	 * @var array() $urlRouteParams
	 */
	protected $urlRouteParams = array();
	
	/**
	 * The incoming request's method (GET, PUT, POST....).
	 * @var String $verb
	 */
	protected $verb;
	
	/**
	 * The url of the request.
	 * @var String $requestUrl
	 */
	protected $requestUrl;
	
	/**
	 * If the request should check for CSRF, defined in the Route class
	 * @var boolean $needCheckCSRF
	 */
	protected $needCheckCSRF;
	
	
	/**
	 * If the request is an API call, when the request url starts with "api"
	 * example: /api/Users
	 * @var boolean $isApi
	 */
	protected $isApi;
	
	/**
	 * If the request is an Ajax call, checked if the header "xmlhttprequest" is set in request
	 * @var boolean $isAjax
	 */
	protected $isAjax;
	
	/**
	 * @var boolean
	 */
	protected $hasMatch = false;
	
	/**
	 * @var Route
	 * @see	Route
	 */
	protected $matchedRoute;
	
	
	function __construct() {
		
		$this->setHeaders(getallheaders());
		$this->verb = $_SERVER['REQUEST_METHOD'];
		
		$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
		
		$this->requestUrl = $escaped_url;
		$this->isApi = strpos($this->requestUrl, "/api") === 0 ? true : false;
		$this->isAjax = strtolower(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH')) === 'xmlhttprequest' ? true : false;
		
		$this->parseIncomingParams();
	
	}
	
	public function parseIncomingParams() {
		
		if (isset($_SERVER['QUERY_STRING'])) {
			parse_str($_SERVER['QUERY_STRING'], $this->GetParams);
			foreach($this->GetParams as $field => $value) {
				$this->GetParams[$field] = HelperUtils::xssafe($value);
			
			}
		}
	
		$body = file_get_contents("php://input");
		$content_type = false;
		if(isset($_SERVER['CONTENT_TYPE'])) {
			$content_type = $_SERVER['CONTENT_TYPE'];
		}
		$postvars = array();
		switch($content_type) {
			case "application/json":
				$body_params = json_decode($body);
				if($body_params) {
					foreach($body_params as $param_name => $param_value) {
						$this->PostParams[$param_name] = HelperUtils::xssafe($param_value);
					}
				}
				break;
			case "application/x-www-form-urlencoded":
				parse_str($body, $postvars);
				foreach($postvars as $field => $value) {
					$this->PostParams[$field] = HelperUtils::xssafe($value);
	
				}
				break;
			case "application/x-www-form-urlencoded; charset=UTF-8":
				parse_str($body, $postvars);
				foreach($postvars as $field => $value) {
					$this->PostParams[$field] = HelperUtils::xssafe($value);
	
				}
				break;
			default:
				$postvars = $_POST;
				foreach($postvars as $field => $value) {
					$this->PostParams[$field] = HelperUtils::xssafe($value);
	
				}
				break;
		}
		if($_FILES) {
			$this->PostParams["files"] = $_FILES;
		}
		
	}
	
	public function parseUrlParams($argument_keys, $matches) {
		foreach ($argument_keys as $key => $name) {
			if (isset($matches[$key])) {
				$this->urlRouteParams[$name] = $matches[$key];
			}
		}
	}
	
	
	public function getPostParams() {
		return $this->PostParams;
	}
	public function setPostParams($PostParams) {
		$this->PostParams = $PostParams;
	}
	public function setPostParamsKeyValue($key,$value) {
		$this->PostParams[$key] = $value;
	}
	public function getGetParams() {
		return $this->GetParams;
	}
	public function setGetParams($GetParams) {
		$this->GetParams = $GetParams;
	}
	public function setGetParamsKeyValue($key,$value) {
		$this->GetParams[$key] = $value;
	}
	
	/**
	 * @see $urlRouteParams
	 */
	public function getUrlRouteParams() {
		return $this->urlRouteParams;
	}
	public function setUrlRouteParams($urlRouteParams) {
		$this->urlRouteParams = $urlRouteParams;
	}
	public function setUrlRouteParamsKeyValue($key,$value) {
		$this->urlRouteParams[$key] = $value;
	}
	
	public function getException() {
		return $this->exception;
	}
	public function setException($exception) {
		$this->exception = $exception;
		return $this;
	}
	public function getVerb() {
		return $this->verb;
	}
	public function setVerb($verb) {
		$this->verb = $verb;
		return $this;
	}
	public function getRequestUrl() {
		return $this->requestUrl;
	}
	public function setRequestUrl($requestUrl) {
		$this->requestUrl = $requestUrl;
		return $this;
	}
	public function getNeedCheckCSRF() {
		return $this->needCheckCSRF;
	}
	public function setNeedCheckCSRF($checkCSRF) {
		$this->needCheckCSRF = $checkCSRF;
		return $this;
	}
	public function getIsApi() {
		return $this->isApi;
	}
	public function setIsApi($isApi) {
		$this->isApi = $isApi;
		return $this;
	}
	public function getIsAjax() {
		return $this->isAjax;
	}
	public function setIsAjax($isAjax) {
		$this->isAjax = $isAjax;
		return $this;
	}
	
	public function getHasMatch() {
		return $this->hasMatch;
	}
	public function setHasMatch($hasMatch) {
		$this->hasMatch = (int)$hasMatch;
	}
	
	public function getMatchedRoute() {
		return $this->matchedRoute;
	}
	public function setMatchedRoute($route) {
		$this->matchedRoute = $route;
	}
	
}