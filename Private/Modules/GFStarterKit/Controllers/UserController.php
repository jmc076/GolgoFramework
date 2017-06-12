<?php
namespace Modules\GFStarterKit\Controllers;

use Controllers\GFSessions\GFSessionController;

use Modules\GFStarterKit\Entities\UserManagement\UserAnonym;
use Modules\GFStarterKit\GFDoctrineManager;
use Helpers\HelperUtils;
use Modules\GFStarterKit\Entities\UserManagement\UserRegistered;

if (version_compare(phpversion(), '5.5.0', '<')) {
	require("password.php");
}

class UserController {




	public static function getCurrentUserModel() {
		$sessionController = GFSessionController::getInstance();
		$sessionModel = $sessionController->getSessionModel();
		if($sessionModel->getStatus() == false) {
			$model = new UserAnonym();
			return $model;

		} elseif ($userid = $sessionModel->getUserId() != 0) {
			$userModel = $sessionModel->getUserModel();
			if (class_exists($userModel)) {
				$model = new $userModel();
				$model = $model->loadById(GFDoctrineManager::getEntityManager(), $userid);
				return $model;
			} else {
				$model = new UserAnonym();
				return $model;
			}
		}
	}


	/*
	 * Logs a user in
	 * @param string $email
	 * @param string $password
	 * @param bool $remember
	 * @return array $return
	 */
	public function login($user, $password)
	{
		$return = array();
		$return['error'] = true;


		if ($this->isBlocked()) {
			$return['message'] = ERROR_USER_BLOCKED;
			return $return;
		}

		$uid = $this->getUID($user);

		if(!$uid) {
			$return['message'] = ERROR_USER_NAME_NOT_FOUND;
			$this->addAttempt();
			return $return;
		}

		$user = $this->getUser($uid);

		if (!password_verify($password, $user['password'])) {
			$return['message'] = ERROR_USER_PASSWORD_MISSMATCH;
			$this->addAttempt();
			return $return;
		}

		if ($user['is_active'] != 1) {
			$return['message'] = ERROR_USER_NOT_ACTIVE;
			return $return;
		}

		$userModel = new UserRegistered();
		$return['user_model'] = $userModel->loadById($this->em, $user['uid']);
		$return['error'] = false;


		return $return;
	}

	/**
	* Gets user data for a given UID and returns an array
	* @param int $uid
	* @return array $data
	*/
	private function getUser($uid)
	{

		$dbal = GFDoctrineManager::getDoctrineDBAL();

		$sql = "SELECT password, isactive FROM ".GF_TABLE_USERS." WHERE id = :id";
		$stmt = $dbal->prepare($sql);
		$stmt->bindValue("id", $uid);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			return false;
		}

		$row = $stmt->fetch();

		if (!$row) {
			return false;
		}

		$row['uid'] = $uid;
		return $row;
	}



	/**
	 * Gets UID for a given email address and returns an array
	 * @param string $email
	 * @return array $uid
	 */

	public function getUID($user)
	{
		$dbal = GFDoctrineManager::getDoctrineDBAL();

		$sql = "SELECT id FROM ".GF_TABLE_USERS." WHERE username = :user";
		$stmt = $dbal->prepare($sql);
		$stmt->bindValue("user", $user);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			return false;
		}

		$row = $stmt->fetch();
		return $row['id'];
	}


	/**
	 * Checks if an email is already in use
	 * @param string $email
	 * @return boolean
	 **/
	public function isEmailTaken($email) {

		$dbal = GFDoctrineManager::getDoctrineDBAL();

		$sql = "SELECT * FROM ".GF_TABLE_USERS." WHERE email = :email";
		$stmt = $dbal->prepare($sql);
		$stmt->bindValue("email", $email);
		$stmt->execute();

		if ($stmt->rowCount() == 0) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if the username is already in use
	 * @param string $email
	 * @return boolean
	 **/
	public function isUserTaken($user) {
		$dbal = GFDoctrineManager::getDoctrineDBAL();

		$sql = "SELECT * FROM ".GF_TABLE_USERS." WHERE username = :user";
		$stmt = $dbal->prepare($sql);
		$stmt->bindValue("user", $user);
		$stmt->execute();


		if ($stmt->rowCount() == 0) {
			return false;
		}

		return true;
	}

	/**
	 * Hashes provided password with Bcrypt
	 * @param string $password
	 * @param string $password
	 * @return string
	 **/
	public function getHash($password) {
		return password_hash($password, PASSWORD_BCRYPT, ['cost' => PASSWORD_BCRYPT_COST]);
	}

	/**
	 * Verifies that a password is valid and respects security requirements
	 * @param string $password
	 * @return array $return
	 **/
	private function validatePassword($password) {
		$return = array();
		$return['error'] = true;

		if (strlen($password) < (int)PASSWORD_MIN_LENGTH ) {
			$return['error_type'] = PASSWORD_MIN_LENGTH;
			return $return;
		}

		$return['error'] = false;
		return $return;
	}


	/**
	 * Verifies that an email is valid
	 * @param string $email
	 * @return array $return
	 **/
	private function validateEmail($email) {
		$return = array();
		$return['error'] = true;

		if (strlen($email) < (int)EMAIL_MIN_LENGTH ) {
			$return['error_type'] = EMAIL_MIN_LENGTH;
			return $return;
		} elseif (strlen($email) > (int)EMAIL_MAX_LENGTH ) {
			$return['error_type'] = EMAIL_MAX_LENGTH;
			return $return;
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$return['error_type'] = FILTER_VALIDATE_EMAIL;
			return $return;
		}

		$return['error'] = false;
		return $return;
	}


	/**
	 * Informs if a user is locked out
	 * @return string
	 **/
	public function isBlocked() {
		$ip = HelperUtils::getIp();
		$dbal = GFDoctrineManager::getDoctrineDBAL();

		$this->deleteAttempts($ip, false);

		$sql = "SELECT count(*) FROM ".GF_TABLE_ATTEMPTS." WHERE ip = :ip";
		$stmt = $dbal->prepare($sql);
		$stmt->bindValue("ip", $ip);
		$stmt->execute();

		$attempts = $stmt->fetchColumn(0);

		if($attempts < intval(LOGIN_ATTEMPTS_BEFORE_BLOCK)) {
			return false;
		}
		return true;
	}


	/**
	 * Adds an attempt to database
	 * @return boolean
	 **/
	private function addAttempt() {
		$dbal = GFDoctrineManager::getDoctrineDBAL();
		$ip = HelperUtils::getIp();

		$attempt_expiredate = date("Y-m-d H:i:s", strtotime(LOGIN_ATTEMPTS_MITIGATION_TIME));


		$sql = "INSERT INTO ".GF_TABLE_ATTEMPTS." (ip, expiredate) VALUES (:ip, :expire)";
		$stmt = $dbal->prepare($sql);
		$stmt->bindValue("ip", $ip);
		$stmt->bindValue("expire", $attempt_expiredate);
		return $stmt->execute();

	}



	/**
	 * Deletes all attempts for a given IP from database
	 * @param string $ip
	 * @param boolean $all = false
	 * @return boolean
	 **/
	private static function deleteAttempts($ip, $all = false) {
		$dbal = GFDoctrineManager::getDoctrineDBAL();

		if($all==true) {

			$sql = "DELETE FROM ".GF_TABLE_ATTEMPTS." WHERE ip = :ip";
			$stmt = $dbal->prepare($sql);
			$stmt->bindValue("ip", $ip);
			return $stmt->execute();

		}

		$sql = "SELECT id, expiredate FROM ".GF_TABLE_ATTEMPTS." WHERE ip = :ip";
		$stmt = $dbal->prepare($sql);
		$stmt->bindValue("ip", $ip);
		$stmt->execute();

		while ($row = $stmt->fetch()) {
			$expiredate = strtotime($row['expiredate']);
			$currentdate = strtotime(date("Y-m-d H:i:s"));
			if($currentdate > $expiredate) {
				$sql = "DELETE FROM ".GF_TABLE_ATTEMPTS." WHERE id = :id";
				$stmt = $dbal->prepare($sql);
				$stmt->bindValue("id", $row['id']);
				$stmt->execute();

			}
		}
	}


	/**
	 * Compare user's password with given password
	 * @param int $userid
	 * @param string $password_for_check
	 * @return bool
	 **/
	public static function comparePasswords($userid, $password_for_check) {
		$user = UserController::getCurrentUserModel();

		if($user instanceof UserAnonym) return false;

		$currentPassword = $user->getPassword();
		if($currentPassword == null) return false;

		return password_verify($password_for_check, $currentPassword);
	}

}