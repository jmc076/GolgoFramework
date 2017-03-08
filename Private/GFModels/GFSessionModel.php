<?php
namespace GFModels;
use Helpers\HelperUtils;

class GFSessionModel {
	protected $userModel;
	protected $userId;
	protected $userIp;
	protected $userCart;
	protected $userExtra;
	protected $status;



	public function initializeValues() {
		$this->userModel = SESSION_ANONYM;
		$this->userId = 0;
		$this->userIp = HelperUtils::getIp();
		$this->userCart = array();
		$this->userExtra = array();
		$this->status = false;
	}
	public function getUserModel() {
		return $this->userModel;
	}
	public function setUserModel($userModel) {
		$this->userModel = $userModel;
		return $this;
	}
	public function getUserId() {
		return $this->userId;
	}
	public function setUserId($userId) {
		$this->userId = $userId;
		return $this;
	}
	public function getUserIp() {
		return $this->userIp;
	}
	public function setUserIp($userIp) {
		$this->userIp = $userIp;
		return $this;
	}
	public function getUserCart() {
		return $this->userCart;
	}
	public function setUserCart($userCart) {
		$this->userCart = $userCart;
		return $this;
	}
	public function getUserExtra() {
		return $this->userExtra;
	}
	public function setUserExtra($userExtra) {
		$this->userExtra = $userExtra;
		return $this;
	}
	public function getStatus() {
		return $this->status;
	}
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}

}