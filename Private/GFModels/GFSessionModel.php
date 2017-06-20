<?php
namespace GFModels;
use Helpers\HelperUtils;
use Controllers\GFSessions\GFSessionController;

class GFSessionModel {
	protected $userModel;
	protected $userId;
	protected $userIp;
	protected $userCart;
	protected $userExtra;
	protected $status;
	protected $userLang;



	public function initializeValues() {
		$this->userModel = null;
		$this->userId = 0;
		$this->userIp = HelperUtils::getIp();
		$this->userCart = array();
		$this->userExtra = array();
		$this->status = false;
		$this->userLang = "";
	}
	public function getUserModel() {
		return $this->userModel;
	}
	public function setUserModel($userModel) {
		$this->userModel = $userModel;
		$this->autoSave();
		return $this;
	}
	public function getUserId() {
		return $this->userId;
	}
	public function setUserId($userId) {
		$this->userId = $userId;
		$this->autoSave();
		return $this;
	}
	public function getUserIp() {
		return $this->userIp;
	}
	public function setUserIp($userIp) {
		$this->userIp = $userIp;
		$this->autoSave();
		return $this;
	}
	public function getUserCart() {
		return $this->userCart;
	}
	public function setUserCart($userCart) {
		$this->userCart = $userCart;
		$this->autoSave();
		return $this;
	}
	public function getUserExtra() {
		return $this->userExtra;

	}
	public function setUserExtra($userExtra) {
		$this->userExtra = $userExtra;
		$this->autoSave();
		return $this;
	}
	public function getStatus() {
		return $this->status;
	}
	public function setStatus($status) {
		$this->status = $status;
		$this->autoSave();
		return $this;
	}
	public function getUserLang() {
		return $this->userLang;
	}
	public function setUserLang($userLang) {
		$this->userLang = $userLang;
		$this->autoSave();
		return $this;
	}

	private function autoSave() {
		$session = GFSessionController::getInstance();
		$session->getSession()->saveSessionModel($this);
	}


}