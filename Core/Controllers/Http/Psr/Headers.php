<?php

namespace Core\Controllers\Http\Psr;

use Core\Helpers\Collection;

class Headers extends Collection {


	public static function getAuthorization() {
		$authorization = null;
		if (is_callable('getallheaders')) {
			$headers = getallheaders ();
			if (isset($headers['Authorization'])) {
				$authorization = $headers['Authorization'];
			}
		}

		return $authorization;
	}

	/**
	 * Return array of HTTP header names and values.
	 * This method returns the _original_ header name
	 * as specified by the end user.
	 *
	 * @return array
	 */
	public function all() {
		return parent::all();
	}

	/**
	 * Set HTTP header value
	 *
	 * This method sets a header value. It replaces
	 * any values that may already exist for the header name.
	 *
	 * @param string $key
	 *        	The case-insensitive header name
	 * @param string $value
	 *        	The header value
	 */
	public function set($key, $value) {
		if (!is_array($value)) {
			$value = array($value);
		}
		parent::set($this->normalizeKey($key), array('value' => $value));
	}

	/**
	 * Get HTTP header value
	 *
	 * @param string $key
	 *        	The case-insensitive header name
	 * @param mixed $default
	 *        	The default value if key does not exist
	 *
	 * @return string[]
	 */
	public function get($key, $default = null) {
		if ($this->has($key)) {
			return parent::get($this->normalizeKey($key))['value'];
		}

		return $default;
	}


	/**
	 * Add HTTP header value
	 *
	 * This method appends a header value. Unlike the set() method,
	 * this method _appends_ this new value to any values
	 * that already exist for this header name.
	 *
	 * @param string $key
	 *        	The case-insensitive header name
	 * @param array|string $value
	 *        	The new header value(s)
	 */
	public function add($key, $value) {
		$oldValues = $this->get($key,[]);
		$newValues = is_array($value) ? $value : array($value);
		$this->set($key,array_merge($oldValues, array_values($newValues)));
	}

	/**
	 * Does this collection have a given header?
	 *
	 * @param string $key
	 *        	The case-insensitive header name
	 *
	 * @return bool
	 */
	public function has($key) {
		return parent::has($this->normalizeKey($key));
	}

	/**
	 * Remove header from collection
	 *
	 * @param string $key
	 *        	The case-insensitive header name
	 */
	public function remove($key) {
		parent::remove($this->normalizeKey($key));
	}

	/**
	 * Normalize header name
	 *
	 * This method transforms header names into a
	 * normalized form. This is how we enable case-insensitive
	 * header names in the other methods in this class.
	 *
	 * @param string $key
	 *        	The case-insensitive header name
	 *
	 * @return string Normalized header name
	 */
	public function normalizeKey($key) {
		$key = strtr(strtolower($key),'_','-');

		return $key;
	}
}
