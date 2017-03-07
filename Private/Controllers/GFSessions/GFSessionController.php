<?php
namespace Controllers\GFSessions;

use Helpers\HelperUtils;
use Controllers\Interfaces\SessionControllerInterface;
use Models\SessionUserModel;
use Models\GFSessionModel;

class GFSessionController {

	private $session;
	protected $sessionModel;
	private static $instancia;

	public static function getInstance()
	{
		if (  !self::$instancia instanceof self)
		{
			self::$instancia = new self;

		}
		return self::$instancia;
	}

	public function init() {
		@session_start();


		if(!isset($this->session["sessionModel"])) {
			$this->sessionModel = new GFSessionModel();
			$this->sessionModel->initializeValues();
			$this->session["sessionModel"] = $this->sessionModel;
		} else {
			$this->sessionModel = $this->session["sessionModel"];
		}

		self::initSessionTimings();
		self::get_token_id();
		self::get_token();
	}

	private function __construct()
	{
		$this->session = new ScopedSessionController();
		$this->init();
	}


	public function regenerateSession() {
		session_regenerate_id(true);
		$this->session['CREATED'] = time();
	}

	public function getSessionId() {
		return session_id();
	}

	public static function setSessionId($sessionID) {
		session_id($sessionID);
	}


	public static function exitSession() {
		$_SESSION = array();
		if (ini_get("session.use_cookies")) {
		    $params = session_get_cookie_params();
		    setcookie(session_name(), '', time() - 42000,
		        $params["path"], $params["domain"],
		        $params["secure"], $params["httponly"]
		    );
		}
		@session_unset();
		@session_destroy();
	}

	public static function expireSession() {
		$_SESSION = array();
		$_SESSION["oldSessionRedirect"] = $_SERVER['REQUEST_URI'];
		@session_unset();
		@session_destroy();
	}

	public static function initSessionTimings(){
		if (!isset($_SESSION['CREATED'])) {
			$_SESSION['CREATED'] = time();
		} else if (time() - $_SESSION['CREATED'] > SESSION_LENGTH) {
			self::regenerateSession();
		}
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_LENGTH)) {
			self::expireSession();
		}

		$_SESSION['LAST_ACTIVITY'] = time();
	}


	public static function getCurrentUserModel() {
		$userModel = $_SESSION["sessionData"]["user_model"];
		if (class_exists($userModel)) {
			$model = new $userModel();
			return $model;
		} else {
			$user = SESSION_ANONYM;
			$model = new $user();
			return $model;
		}
	}

	public static function get_token_id() {
		if(isset($_SESSION['token_id'])) {
			return $_SESSION['token_id'];
		} else {
			$token_id = HelperUtils::getRandomKey(10);
			$_SESSION['token_id'] = $token_id;
			return $token_id;
		}
	}
	public static function get_token() {
		if(isset($_SESSION['token_value'])) {
			return $_SESSION['token_value'];
		} else {
			$token = hash('sha256', HelperUtils::getRandomKey(500));
			$_SESSION['token_value'] = $token;
			return $token;
		}

	}
	//FOR CSRF
	public static function check_valid($dataArray, $exception) {
		if(isset($dataArray[self::get_token_id()])) {
			if ($dataArray[self::get_token_id()] == self::get_token()) {
				return true;
			} else {

				$exception->invalidCSRF();
			}
		} else {
			$exception->missingCSRF();
		}
	}
	public function getSessionModel() {
		return $this->sessionModel;
	}
	public function setSessionModel($sessionModel) {
		$this->sessionModel = $sessionModel;
		return $this;
	}


}
