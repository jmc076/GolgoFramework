<?php
namespace Core\Controllers\GFSessions;




use Core\GFModels\GFSessionModel;
use Core\Helpers\Utils;

class GFSessionController {

	use ScopedSessionTrait;

	private static $instancia;

	private function __construct() {

	}

	public static function getInstance() {
		if ( !self::$instancia instanceof self) {
			self::$instancia = new self;

		}
		self::$instancia->start();
		return self::$instancia;
	}

	public function start() {
		$this->scopedSessionInit(GF_GLOBAL_SESSION);
		$this->initSessionModel();
		$this->handleSessionTimings();
		$this->initCSRF();
	}

	public function initSessionModel() {
		if(!$this->isKeySet("sessionModel") || $this->get("sessionModel") == null) {
			$model =  new GFSessionModel();
			$model->initializeValues();
			$this->put("sessionModel", $model);
		}
	}

	public  function handleSessionTimings(){
		if (!$this->isKeySet("CREATED")) {
			$this->put("CREATED", time());
		} else if (time() - $this->get("CREATED") > SESSION_LENGTH) {
			$this->regenerateSession();
		}
		if ($this->isKeySet("LAST_ACTIVITY") && (time() - $this->get("LAST_ACTIVITY") > SESSION_LENGTH)) {
			self::expireSession();
		}
		$this->put("LAST_ACTIVITY", time());
	}

	public function getSessionModel() {
		return $this->get("sessionModel");
	}

	public function saveSessionModel($model) {
		$this->put("sessionModel", $model);
	}

	public static function startManagingSession() {
		@session_start();
	}

	public function regenerateSession() {
		session_regenerate_id(true);
		$this->put("CREATED", time());
	}

	public function getSessionId() {
		return session_id();
	}

	public  function setSessionId($sessionID) {
		session_id($sessionID);
	}

	public  function exitSession() {
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

	public  function expireSession() {
		$_SESSION = array();
		@session_unset();
		$this->initSessionModel();
		$this->put("oldSessionRedirect", $_SERVER['REQUEST_URI']);

	}

	public function setSessionModel($sessionModel) {
		$thisModel = $sessionModel;
		$this->saveSessionModel($thisModel);
		return $this;
	}

	/////////////////CSRF////////////////////////
	private function initCSRF() {
		$this->getTokenId();
		$this->getTokenValue();
	}


	public function getSessionCsrfName() {
		return $this->getTokenId();
	}

	public function getSessionCsrfValue() {
		return $this->getTokenValue();
	}

	public function isValidCSRF($dataArray) {
		if(isset($dataArray[$this->getTokenId()])) {
			if ($dataArray[$this->getTokenId()] == $this->getTokenValue()) {
				$this->resetTokenValue();
				return true;
			} else {
				$this->resetTokenValue();
				return false;
			}
		} else {
			$this->resetTokenValue();
			return false;
		}
	}


	public function getTokenId() {

		if($this->isKeySet("token_id")) {
			return $this->get("token_id");
		} else {
			$token_id = Utils::getRandomKey(10);
			$this->put("token_id", $token_id);
			return $token_id;
		}
	}

	public function getTokenValue() {
		if($this->isKeySet("token_value")) {
			return $this->get("token_value");
		} else {
			$token = hash('sha256', Utils::getRandomKey(500));
			$this->put("token_value", $token);
			return $token;
		}

	}

	public function resetTokenValue() {
		$token = hash('sha256', Utils::getRandomKey(500));
		$this->put("token_value", $token);
		return $token;

	}


}
