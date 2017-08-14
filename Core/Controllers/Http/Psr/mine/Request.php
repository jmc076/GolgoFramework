<?php

namespace Core\Controllers\Http\Psr\mine;

use Core\Helpers\Collection;
use Core\Controllers\Http\Psr\Interfaces\RequestInterface;
use Core\Controllers\Http\Psr\Interfaces\UriInterface;
use Core\Controllers\Http\Psr\Interfaces\StreamInterface;

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
	
	protected $isCSRFProtected;
	
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
	
	
	public function __construct($method, UriInterface $uri, Headers $headers, array $cookies, array $serverParams, StreamInterface $body, array $uploadedFiles = []) {
		$this->body = new Stream('php://input');
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

}
