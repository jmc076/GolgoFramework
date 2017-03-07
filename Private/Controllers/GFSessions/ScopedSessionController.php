<?php
namespace Controllers\GFSessions;


class ScopedSessionController {

	const GF_GLOBAL_SESSION = "gf_global";

	private $scope;

	public function __construct($scope = self::GF_GLOBAL_SESSION) {
		$this->scope = $scope;
	}

	public function getSession(){
		if(isset($_SESSION[$this->scope])) {
			return $_SESSION[$this->scope];
		} else {
			$_SESSION[$this->scope] = array();
			return $_SESSION[$this->scope];
		}
	}


	public function put($key, $value) {
		try {
			$session = $this->getSessionScope();
			$session[$key] = $value;
		} catch (Exception $e) {
			return false;
		}
		return true;

	}
	public function safePut($key, $value){
		try {
			$session = $this->getSessionScope();
			if(!isset($session[$key])){
				$session[$key] = $value;
				return true;
			}
			return false;

		} catch (Exception $e) {
			return false;
		}
	}

	public function get($key) {
		try {
			$session = $this->getSessionScope();
			return isset($session[$key]) ? $session[$key]: null;
		} catch (Exception $e) {
			return null;
		}
	}

	public function getAndDelete($key){
		try {
			$session = $this->getSessionScope();
			$value = isset($session[$key]) ? $session[$key]: null;
			$this->delete($key);
			return $value;
		} catch (Exception $e) {
			return null;
		}
	}

	public function delete($key) {
		try {
			$session = $this->getSessionScope();
			unset($session[$key]);
		} catch (Exception $e) {
			return false;
		}
		return  true;
	}


	public function getScope() {
		return $this->scope;
	}
	public function setScope($scope) {
		$this->scope = $scope;
		return $this;
	}



}
