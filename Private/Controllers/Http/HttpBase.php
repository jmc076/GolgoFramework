<?php

/**
 * @author Diego
 * @version 1.0
 */
namespace Controllers\Http;

abstract class HttpBase {

	protected $headers = array();
	protected $httpVersion = '1.1';
	protected $charset = 'UTF-8';

	/**
	 * @var integer
	 */
	protected $statusCode = 200;

	public function hasHeader($name) {
		return isset ($this->headers[strtolower($name)]);
	}

	public function getAllHeadersAsArray() {
		$result = array ();
		foreach ($this->headers as $header) {
			$result[$header[0]] = $header[1];
		}
		return $result;
	}

	public function getHeaderAsString($name) {
		$name = strtolower($name);

		if (isset($this->headers[$name])) {
			return implode( ',', $this->headers[$name][1]);
		}
		return null;
	}

	public function getHeaderAsArray($name) {
		$name = strtolower($name);

		if (isset($this->headers[$name])) {
			return $this->headers[$name][1];
		}

		return array ();
	}

	public function updateHeader($name, $value) {
		$this->headers[strtolower($name)] = [$name,(array)$value];
	}

	public function setHeader($name, $value) {
		$keyName = strtolower($name);
		if (isset($this->headers[$keyName])) {
			$this->headers[$keyName][1] = array_merge($this->headers[$keyName][1],(array)$value);
		} else {
			$this->headers[$keyName] = [$name,(array)$value ];
		}

	}

	public function setHeaders(array $headers) {
		foreach ($headers as $name => $value) {
			$this->setHeader($name,$value);
		}
	}

	public function removeHeader($name) {
		$name = strtolower($name);
		if (!isset($this->headers[$name])) {
			return false;
		}
		unset($this->headers[$name]);
		return true;
	}

	public function getHttpVersion() {
		return $this->httpVersion;
	}

	public function setHttpVersion($httpVersion) {
		$this->httpVersion = $httpVersion;
	}

	public function getCharset() {
		return $this->charset;
	}

	public function setCharset($charset) {
		$this->charset = $charset;
	}

	public function getStatusCode() {
		return $this->statusCode;
	}
	public function setStatusCode($statusCode) {
		$this->statusCode = $statusCode;
	}
}
