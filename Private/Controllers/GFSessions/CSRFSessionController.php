<?php
namespace Controllers\GFSessions;

use Helpers\HelperUtils;

class CSRFSessionController {

	private $session;
	private static $instancia;

	private function __construct() {
		$sessionController = new ScopedSessionController(ScopedSessionController::CSRF_SCOPE);
		$this->session = $sessionController->getSession();
		$this->initialize();
	}

	public static function getInstance() {
		if (  !self::$instancia instanceof self) {
			self::$instancia = new self;

		}
		return self::$instancia;
	}


	public function isValid($dataArray) {
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

	private function initialize() {
		$this->getTokenId();
		$this->getTokenValue();
	}

	public function getTokenId() {

		if(isset($this->session['token_id'])) {
			return $this->session['token_id'];
		} else {
			$token_id = HelperUtils::getRandomKey(10);
			$this->session['token_id'] = $token_id;
			return $token_id;
		}
	}

	public function getTokenValue() {
		if(isset($this->session['token_value'])) {
			return $this->session['token_value'];
		} else {
			$token = hash('sha256', HelperUtils::getRandomKey(500));
			$this->session['token_value'] = $token;
			return $token;
		}

	}

	public function resetTokenValue() {
		$token = hash('sha256', HelperUtils::getRandomKey(500));
		$this->session['token_value'] = $token;
		return $token;

	}
}