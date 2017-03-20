<?php
namespace Modules\GFStarterKit\Controllers;

use Controllers\GFSessions\GFSessionController;
use Modules\GFStarterKit\Entities\UserManagement\UserAnonym;
use Modules\GFStarterKit\GFDoctrineManager;

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

	/**
	 * Deletes all attempts for a given IP from database
	 * @param string $ip
	 * @param boolean $all = false
	 * @return boolean
	 */
	private static function deleteAttempts($ip, $all = false)
	{
		$dbal = GFDoctrineManager::getDoctrineDBAL();

		if($all==true) {
			$query = $this->dbh->prepare("DELETE FROM {$this->config['table_attempts']} WHERE ip = ?");
			return $query->execute(array($ip));

			$sql = "DELETE FROM ".GF_TABLE_USERS." WHERE ip = :ip";
			$stmt = $dbal->prepare($sql);
			$stmt->bindValue("ip", $ip);
			return $stmt->execute();

		}


		$query = $this->dbh->prepare("SELECT id, expiredate FROM {$this->config['table_attempts']} WHERE ip = ?");
		$query->execute(array($ip));

		while ($row = $query->fetch(\PDO::FETCH_ASSOC)) {
			$expiredate = strtotime($row['expiredate']);
			$currentdate = strtotime(date("Y-m-d H:i:s"));
			if($currentdate > $expiredate) {
				$queryDel = $this->dbh->prepare("DELETE FROM {$this->config['table_attempts']} WHERE id = ?");
				$queryDel->execute(array($row['id']));
			}
		}
	}


	/**
	 * Compare user's password with given password
	 * @param int $userid
	 * @param string $password_for_check
	 * @return bool
	 */
	public static function comparePasswords($userid, $password_for_check)
	{
		$user = UserController::getCurrentUserModel();

		if($user instanceof UserAnonym) return false;

		$currentPassword = $user->getPassword();
		if($currentPassword == null) return false;

		return password_verify($password_for_check, $currentPassword);
	}

}