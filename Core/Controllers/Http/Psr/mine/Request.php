<?php

namespace Core\Controllers\Http\Psr\mine;

use Core\Helpers\Collection;
use Core\Controllers\Http\Psr\Interfaces\RequestInterface;
use Core\Controllers\Http\Psr\Interfaces\UriInterface;
use Core\Controllers\Http\Psr\Interfaces\StreamInterface;
use Core\Controllers\Http\Psr\UploadedFile;
use Core\Controllers\ExceptionController;

/**
 * Headers
 *
 * This class represents a collection of HTTP headers
 * that is used in both the HTTP request and response objects.
 * It also enables header name case-insensitivity when
 * getting or setting a header value.
 *
 * Each HTTP header can have multiple values. This class
 * stores values into an array for each header name. When
 * you request a header value, you receive an array of values
 * for that header.
 */
class Request extends Message implements RequestInterface {

	
	protected $method;
	
	protected $uri;
	
	protected $postParams;
	
	protected $getParams;
	
	/**
	 * Uri parsed params from path
	 * @var string
	 */
	protected $routeParams;
	
	protected $cookies;
	
	protected $bodyParsers = [];
	
	protected $uploadedFiles;
	
	protected $isApiRequest;
	
	/**
	 * If the uri has a matched route
	 * @var boolean
	 */
	protected $hasMatch = false;
	
	/**
	 * @var Route
	 * @see	Route
	 */
	protected $matchedRoute;
	
	
	public function __construct($method, UriInterface $uri, Headers $headers, array $cookies, StreamInterface $body, array $uploadedFiles = array()) {
		$this->method = $method;
		$this->uri = $uri;
		$this->headers = $headers;
		$this->cookies = $cookies;
		$this->body = $body;
		$this->uploadedFiles = !is_null($uploadedFiles) ? $uploadedFiles : array();
		
		$this->isApiRequest = strpos($this->uri->getPath(), "/api/") !== false ? true : false;
	}
	
	public static function parseRequest() {
		
		$stream = fopen('php://temp', 'w+');
		stream_copy_to_stream(fopen('php://input', 'r'), $stream);
		$streamBody = new Stream($stream);
		$streamBody->rewind();
		
		$header = new Headers();
		foreach (getallheaders() as $key => $value) {
			$header->add($key, $value);
		}
		$method = $_SERVER['REQUEST_METHOD'];
		
		$url =  "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		$escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
		$uri = Uri::createFromString($escaped_url);
		
		$headerCookies = getallheaders()['Cookie'];
		$cookieData = Cookies::parseHeader($headerCookies);
		$cookie = new Cookies($cookieData);
		
		$files = UploadedFile::parseRequestFiles();
		
		return new static($method, $uri, $header, $cookie, $streamBody, $files);
		
	}
	/**
	 * {@inheritDoc}
	 * @see \Core\Controllers\Http\Psr\Interfaces\RequestInterface::getRequestTarget()
	 */
	public function getRequestTarget() {
		
		if ($this->uri === null) {
			return '/';
		}
		
		$path = $this->uri->getPath();
		$path = '/' . ltrim($path, '/');
		
		$query = $this->uri->getQuery();
		if ($query) {
			$path .= '?' . $query;
		}
		
		return $path;

	}

	/**
	 * {@inheritDoc}
	 * @see \Core\Controllers\Http\Psr\Interfaces\RequestInterface::withRequestTarget()
	 */
	public function withRequestTarget($requestTarget) {
		if (preg_match('#\s#', $requestTarget)) {
			throw new \InvalidArgumentException('Invalid request target provided; must be a string and cannot contain whitespace');
		}
		$clone = clone $this;
		$clone->requestTarget = $requestTarget;
		$clone->uri = Uri::createFromString($requestTarget);
		
		return $clone;

	}

	/**
	 * {@inheritDoc}
	 * @see \Core\Controllers\Http\Psr\Interfaces\RequestInterface::getMethod()
	 */
	public function getMethod() {
		return $this->method;

	}

	/**
	 * {@inheritDoc}
	 * @see \Core\Controllers\Http\Psr\Interfaces\RequestInterface::withMethod()
	 */
	public function withMethod($method) {
		$clone = clone $this;
		$clone->method = $method;
		
		return $clone;

	}

	/**
	 * {@inheritDoc}
	 * @see \Core\Controllers\Http\Psr\Interfaces\RequestInterface::getUri()
	 */
	public function getUri() {
		return $this->uri;

	}

	/**
	 * {@inheritDoc}
	 * @see \Core\Controllers\Http\Psr\Interfaces\RequestInterface::withUri()
	 */
	public function withUri(UriInterface $uri, $preserveHost = false) {
		$clone = clone $this;
		$clone->uri = $uri;
		
		if (!$preserveHost) {
			if ($uri->getHost() !== '') {
				$clone->headers->set('Host', $uri->getHost());
			}
		} else {
			if ($uri->getHost() !== '' && (!$this->hasHeader('Host') || $this->getHeaderLine('Host') === '')) {
				$clone->headers->set('Host', $uri->getHost());
			}
		}
		
		return $clone;

	}
	
	public function dispatchNoMatch() {
		if($this->isApiRequest) {
			ExceptionController::routeNotFound();
		} else {
			ExceptionController::show404();
		}
	
	}
	
	public function executeRequest() {
	
		if(!$this->hasMatch) {
			$this->dispatchNoMatch();
	
		} else {
			$matchedRoute = $this->getMatchedRoute();
			if($matchedRoute->function != null) {
				call_user_func($matchedRoute->function);
			} else {
				$class = $matchedRoute->getTargetClass();
	
				if ($matchedRoute->getTargetClassMethod() != null) {
					call_user_func_array(array($class, $matchedRoute->getTargetClassMethod()), array());
				} else {
					if(class_exists($class))
						new $class;
					else ExceptionController::classNotFound();
				}
			}
	
	
		}
	}
	public function setMethod($method) {
		$this->method = $method;
		return $this;
	}
	public function setUri($uri) {
		$this->uri = $uri;
		return $this;
	}
	public function getPostParams() {
		return $this->postParams;
	}
	public function setPostParams($postParams) {
		$this->postParams = $postParams;
		return $this;
	}
	public function getGetParams() {
		return $this->getParams;
	}
	public function setGetParams($getParams) {
		$this->getParams = $getParams;
		return $this;
	}
	public function getRouteParams() {
		return $this->routeParams;
	}
	public function setRouteParams($routeParams) {
		$this->routeParams = $routeParams;
		return $this;
	}
	public function getCookies() {
		return $this->cookies;
	}
	public function setCookies($cookies) {
		$this->cookies = $cookies;
		return $this;
	}
	public function getBodyParsers() {
		return $this->bodyParsers;
	}
	public function setBodyParsers($bodyParsers) {
		$this->bodyParsers = $bodyParsers;
		return $this;
	}
	public function addBodyParsers($bodyParsers) {
		$this->bodyParsers[] = $bodyParsers;
		return $this;
	}
	public function getUploadedFiles() {
		return $this->uploadedFiles;
	}
	public function setUploadedFiles($uploadedFiles) {
		$this->uploadedFiles = $uploadedFiles;
		return $this;
	}
	public function getIsApiRequest() {
		return $this->isApiRequest;
	}
	public function setIsApiRequest($isApiRequest) {
		$this->isApiRequest = $isApiRequest;
		return $this;
	}
	public function getHasMatch() {
		return $this->hasMatch;
	}
	public function setHasMatch($hasMatch) {
		$this->hasMatch = $hasMatch;
		return $this;
	}
	public function getMatchedRoute() {
		return $this->matchedRoute;
	}
	public function setMatchedRoute($matchedRoute) {
		$this->matchedRoute = $matchedRoute;
		return $this;
	}
	

}
