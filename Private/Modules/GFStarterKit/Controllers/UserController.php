<?php
use Controllers\GFSessions\GFSessionController;

class UserController {

	private static $user;

	/**
	 * The array with static permissions
	 * @return array
	 */


	public static function getCurrentUserModel() {
		$sessionController = GFSessionController::getInstance();
		$sessionModel = $sessionController->getSessionModel();
		if($sessionModel->getStatus() == false) {
			$model = new $user();
			return $model;

		} elseif ($_SESSION["sessionData"]["user_id"] != 0) {
			$userModel = $_SESSION["sessionData"]["user_model"];
			if (class_exists($userModel)) {
				$model = new $userModel();
				$model = $model->loadById($GLOBALS['em'], $_SESSION["sessionData"]["user_id"]);
				return $model;
			} else {
				$user = SESSION_ANONYM;
				$model = new $user();
				return $model;
			}
		}
	}

}