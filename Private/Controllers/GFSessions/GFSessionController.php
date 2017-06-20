<?php
namespace Controllers\GFSessions;



class GFSessionController {

	private $session;
	protected $sessionModel;
	private static $instancia;
	private $csrf;

	private function __construct() {
		$this->session = new ScopedSessionController(ScopedSessionController::GF_GLOBAL_SESSION);
		$this->session->initSessionModel();
		$this->sessionModel = $this->session->getSessionModel();
		$this->initSessionTimings();
		$this->csrf = CSRFSessionController::getInstance();

	}



	public function getSession() {
		return $this->session;
	}

	public static function getInstance() {
		if ( !self::$instancia instanceof self) {
			self::$instancia = new self;

		}
		return self::$instancia;
	}

	public function getSessionCsrfName() {
		return $this->csrf->getTokenId();
	}

	public function getSessionCsrfValue() {
		return $this->csrf->getTokenValue();
	}

	public static function startManagingSession() {
		@session_start();
	}


	public  function initSessionTimings(){
		if (!$this->session->isKeySet("CREATED")) {
			$this->session->put("CREATED", time());
		} else if (time() - $this->session->get("CREATED") > SESSION_LENGTH) {
			$this->regenerateSession();
		}
		if ($this->session->isKeySet("LAST_ACTIVITY") && (time() - $this->session->get("LAST_ACTIVITY") > SESSION_LENGTH)) {
			self::expireSession();
		}
		$this->session->put("LAST_ACTIVITY", time());
	}


	public function regenerateSession() {
		session_regenerate_id(true);
		$this->session->put("CREATED", time());
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
		$_SESSION["oldSessionRedirect"] = $_SERVER['REQUEST_URI'];

	}


	public function getSessionModel() {
		return $this->sessionModel;
	}

	public function saveSessionModel() {
		$this->session->saveSessionModel($this->sessionModel);
	}

	public function setSessionModel($sessionModel) {
		$this->sessionModel = $sessionModel;
		$this->session->saveSessionModel($this->sessionModel);
		return $this;
	}


	public function getCurrentUserModel() {

	}

}
