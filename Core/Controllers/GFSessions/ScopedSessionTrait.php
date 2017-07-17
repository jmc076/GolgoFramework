<?php
namespace Core\Controllers\GFSessions;


trait ScopedSessionTrait {


	private $scope;


	public function scopedSessionInit($scope = GF_DEFAULT_SESSION) {
		$this->scope = $scope;
		$this->createSessionScope();
	}

	private function createSessionScope() {
		if(!isset($_SESSION[$this->scope])) {
			$_SESSION[$this->scope] = array();
		}
	}

	public function put($key, $value) {
		try {
			$_SESSION[$this->scope][$key] = $value;
		} catch (Exception $e) {
			return false;
		}
		return true;

	}
	public function safePut($key, $value){
		try {
			if(!isset($_SESSION[$this->scope][$key])){
				$_SESSION[$this->scope][$key] = $value;
				return true;
			}
			return false;

		} catch (Exception $e) {
			return false;
		}
	}

	public function get($key) {
		try {
			return isset($_SESSION[$this->scope][$key]) ? $_SESSION[$this->scope][$key]: null;
		} catch (Exception $e) {
			return null;
		}
	}

	public function getAndDelete($key){
		try {
			$value = isset($$_SESSION[$this->scope][$key]) ? $_SESSION[$this->scope][$key]: null;
			$this->delete($key);
			return $value;
		} catch (Exception $e) {
			return null;
		}
	}

	public function delete($key) {
		try {
			unset($_SESSION[$this->scope][$key]);
		} catch (Exception $e) {
			return false;
		}
		return  true;
	}

	public function isKeySet($key) {
		return isset($_SESSION[$this->scope][$key]);
	}


	public function getScope() {
		return $this->scope;
	}
	public function setScope($scope) {
		$this->scope = $scope;
		return $this;
	}



}
