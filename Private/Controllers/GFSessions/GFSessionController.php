<?php
namespace Controllers\GFSessions;


use GFModels\GFSessionModel;

class GFSessionController {

	private $session;
	protected $sessionModel;
	private static $instancia;
	private $csrf;

	private function __construct() {
		@session_start();
		$sessionController = new ScopedSessionController(ScopedSessionController::GF_GLOBAL_SESSION);
		$this->session = $sessionController->getSession();
		$this->init();
		$this->initSessionTimings();
		$this->csrf = CSRFSessionController::getInstance();

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


	/**
	 * Initialize session model object to sessionModel
	 */
	public function init() {

		if(!isset($this->session["sessionModel"])) {
			$this->sessionModel = new GFSessionModel();
			$this->sessionModel->initializeValues();
			$this->session["sessionModel"] = $this->sessionModel;
		} else {
			$this->sessionModel = $this->session["sessionModel"];
		}

	}

	public  function initSessionTimings(){
		if (!isset($this->session['CREATED'])) {
			$this->session['CREATED'] = time();
		} else if (time() - $this->session['CREATED'] > SESSION_LENGTH) {
			$this->regenerateSession();
		}
		if (isset($this->session['LAST_ACTIVITY']) && (time() - $this->session['LAST_ACTIVITY'] > SESSION_LENGTH)) {
			self::expireSession();
		}
		$this->session['LAST_ACTIVITY'] = time();
	}


	public function regenerateSession() {
		session_regenerate_id(true);
		$this->session['CREATED'] = time();
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

	public function setSessionModel($sessionModel) {
		$this->sessionModel = $sessionModel;
		return $this;
	}


}
