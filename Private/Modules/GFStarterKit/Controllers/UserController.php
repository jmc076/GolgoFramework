<?php
namespace Modules\GFStarterKit\Controllers;

use Controllers\GFSessions\GFSessionController;
use Modules\GFStarterKit\Entities\UserManagement\UserAnonym;
use Modules\GFStarterKit\GFEntityManager;

class UserController {

	private static $user;

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
				$model = $model->loadById(GFEntityManager::getEntityManager(), $userid);
				return $model;
			} else {
				$model = new UserAnonym();
				return $model;
			}
		}
	}

}