<?php
namespace Controllers\GFSessions;

use Helpers\HelperUtils;

class CSRFSessionController {

	const CSRF_SCOPE = "gf_csrf";
	private $session;

	public function __construct(){
		$sessionController = new ScopedSessionController(self::CSRF_SCOPE);
		$this->session = $sessionController->getSession();
	}

	public function isValid($dataArray) {
		if(isset($dataArray[$this->getTokenId()])) {
			if ($dataArray[$this->getTokenId()] == $this->getTokenValue()) {
				return true;
			} else {

				return false;
			}
		} else {
			return false;
		}
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
}