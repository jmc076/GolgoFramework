<?php
namespace Modules\GFStarterKit\EntitiesLogic\UserManagementLogic;

use Modules\GFStarterKit\Controllers\UserController;
use Modules\GFStarterKit\Entities\UserManagement\UserRegistered;
use Modules\GFStarterKit\EntitiesLogic\LogicCRUD;
use Modules\GFStarterKit\Utils\Serializor;
use Modules\GFStarterKit\Entities\UserManagement\Permissions;
use Core\Controllers\JWTController;
use Core\Controllers\ExceptionController;
use Core\Helpers\Utils;
use Core\Controllers\MonoLog\LoggerController;


class BaseUserLogic extends LogicCRUD {

	protected $userController;
	public $userTypes = array(USER_ADMIN, USER_REGISTERED, USER_SUPERADMIN);

	function __construct() {
		$this->userController = new UserController();
		parent::__construct();
	}

	/**
	 * Returns Entity managed in this logic.
	 * @return string
	 */
	public function getEntity() {
		return new UserRegistered();
	}

	public function read($dataArray) {
		$return = null;
		if(isset($dataArray["sop"]) && $dataArray["sop"] != "") {
			$model = $this->getEntity();
			switch ($dataArray["sop"]) {
				case "doLogin":
					if(isset($dataArray["user"]) && isset($dataArray["password"])) {

						$result = $this->userController->login($dataArray["user"], $dataArray["password"]);
						if($result["error"] == false) {
							$userModel =  $result["user_model"];
							$userModel->setToken(Utils::getRandomKey());
							$userModel->persistNow();
							$sessionModel = $this->session->getSessionModel();
							$sessionModel->setStatus(true)->setUserId($userModel->getId())->setUserModel($userModel->getModelNameWithNamespace());
							$jwt = new JWTController();
							$jwt->initializeToken(array("token"=>$userModel->getToken()));
							$cadena = $jwt->encodeToken();
							$return = array("result"=>true, "token"=>$cadena);
							LoggerController::get()->logInfo("user loged in..." , array("userID"=>$userModel->getId() ));
						} else {
							ExceptionController::customError("Login failed with code: " . $result["message"], 400);
						}
					} else {
						ExceptionController::customError("missing password and user in form", 400);
					}

					break;
				case "loadAll":
					if($this->checkPrivileges($dataArray) || $this->userModel->getUserType() == USER_ADMIN) {
						$models = $this->getEntity();
						$models = $models->loadAll($this->em, null);
						$return = Serializor::toArray($models,1, null, null, array('password'));
					} else {
						ExceptionController::PermissionDenied();
					}
					break;
				case "loadById":
					if($this->checkPrivileges($dataArray) || $this->userModel->getUserType() == USER_ADMIN) {
						$model = $model->loadById($this->em, $dataArray["id"],true);
						$return = $model;
					} else {
						ExceptionController::PermissionDenied();
					}
					break;
				case "loadAdmins":
					if($this->checkPrivileges($dataArray) || $this->userModel->getUserType() == USER_ADMIN) {
						$models = $this->getEntity();
						$models = $models->loadAdmins($this->em, null,true);
						$return = $models;
					} else {
						ExceptionController::PermissionDenied();
					}
					break;
				default:
					ExceptionController::noSOPFound();
					break;
			}
		} else {
			ExceptionController::noSOPFound();
		}


		//$return = $model->loadAll($this->em, $getParams);
		return $return;
	}

	public function create($dataArray) {
		$return = false;
		if($this->checkPrivileges($dataArray)) {
			if((isset($dataArray["email"]) && $this->userController->isEmailTaken($dataArray["email"]))
					|| (isset($dataArray["user"]) && $this->userController->isUserTaken($dataArray["user"]))) {
						ExceptionController::customError("email or user taken", 409);
					}
					$model = $this->getEntity();
					try {
						$this->assignParams($dataArray,$model);
						$this->em->persist($model);
						$this->em->flush();
						$return = $model->getId();
					} catch (Exception $e) {
						$return = false;
					}

		} else {
			ExceptionController::PermissionDenied();
		}

		return $return;
	}

	public function update($dataArray) {
		$return = false;
		if($this->checkPrivileges($dataArray)) {
			$model = $this->getEntity();
			if(isset($dataArray["id"])) $model = $model->loadById($this->em, $dataArray["id"]);
			else $model = $this->userModel;

			if(isset($dataArray["email"]) && $model->getEmail() != $dataArray["email"] && $this->userController->isEmailTaken($dataArray["email"])) {
				$error = array('error' => "El email ya está en uso.");
				return $error;
			}
			if(isset($dataArray["sop"]) && $dataArray["sop"] != "") {
				switch ($dataArray["sop"]) {
					case "updateSelfUser":
						$model = $this->userModel;
						$this->assignParams($dataArray,$model);
						$this->em->persist($model);
						$this->em->flush();
						$return = true;
						break;
					case "block":
						if($dataArray["id"] != $this->userModel->getId()) {
							$model = $model->loadById($this->em, $dataArray["id"]);
							$model->setIsActive(0);
							$this->em->persist($model);
							$this->em->flush();
							$return = true;
						} else {
							ExceptionController::customError("No puedes bloquearte a ti mismo", 400);
						}
						break;
					case "unblock":
						$model = $model->loadById($this->em, $dataArray["id"]);
						$model->setIsActive(1);
						$this->em->persist($model);
						$this->em->flush();
						$return = true;
						break;
					default:
						ExceptionController::noSOPFound();
						break;
				}
			} else {
				try {
					$this->assignParams($dataArray,$model);
					$this->em->persist($model);
					$this->em->flush();
					$return = $model->getId();
				} catch (Exception $e) {
					$return = false;
				}
			}
		} else {
			ExceptionController::PermissionDenied();
		}




		return $return;
	}

	public function delete($dataArray) {
		if($this->checkPrivileges($dataArray)) {
			if($dataArray["id"] != $this->userModel->getId()) {
				$model = $this->getEntity();
				$model = $model->loadById($this->em, $dataArray["id"]);
				$this->em->remove($model);
				$this->em->flush();
				return true;
			} else return false;
		} else {
			ExceptionController::PermissionDenied();
		}
	}

	public function assignParams($dataArray, &$model) {

		if(isset($dataArray["userType"]) && !empty($dataArray["userType"]) && in_array($dataArray["userType"], $this->userTypes)) {
			if( $dataArray["userType"] != $model->getUserType()) {
				if(($dataArray["userType"] == USER_ADMIN || $dataArray["userType"] == USER_SUPERADMIN) && $this->isAdmin()) {
					$model->setUserType($dataArray["userType"]);
				} else {
					$model->setUserType(USER_REGISTERED);
				}
			}
		} else  {
			$model->setUserType(USER_REGISTERED);
		}

		if(isset($dataArray["isActive"]))
			$model->setIsActive(1);
			else
				$model->setIsActive(0);


				if(isset($dataArray["email"])) {
					$model->setEmail($dataArray["email"]);
				}

				if(isset($dataArray["name"])) {
					$model->setName($dataArray["name"]);
				}


				if(isset($dataArray["firstName"])) {
					$model->setFirstName($dataArray["firstName"]);
				}

				if(isset($dataArray["lastName"])) {
					$model->setLastName($dataArray["lastName"]);
				}

				if(isset($dataArray["bio"])) {
					$model->setBio($dataArray["bio"]);
				}

				if(isset($dataArray["telephone"])) {
					$model->setTelephone($dataArray["telephone"]);
				}

				if(isset($dataArray["user"]) && $dataArray["user"] != "") {
					$model->setUserName($dataArray["user"]);
				}

				if(isset($dataArray["userName"])) {
					$model->setUserName($dataArray["userName"]);
				}

				if(isset($dataArray["password"]) && $dataArray["password"] != "") {
					if(isset($dataArray["repeat_pass"]) && $dataArray["repeat_pass"] != "") {
						if($dataArray["password"] == $dataArray["repeat_pass"] ) {
							$model->setPassword($this->userController->getHash($dataArray["password"]));
						} else {
							ExceptionController::passwordMissmatch();
						}
					} else {
						ExceptionController::customError("To set new password please fill the repeat password field", 400);
					}

				}

				if(isset($dataArray["lastIp"])) {
					$model->setLastIp($dataArray["lastIp"]);
				}

				if(isset($dataArray["lastLogin"])) {
					$model->setLastLogin($dataArray["lastLogin"]);
				}

				if(isset($dataArray["sessionId"])) {
					$model->setSessionId($dataArray["sessionId"]);
				}

				if(isset($dataArray["activationKey"])) {
					$model->setActivationKey($dataArray["activationKey"]);
				}

				if(isset($dataArray["userAvatar"])) {
					$model->setUserAvatar($dataArray["userAvatar"]);
				}

				if(isset($dataArray["shouldChangePassword"])) {
					$model->setShouldChangePassword(1);
				} else {
					$model->setShouldChangePassword(0);
				}

				if(isset($dataArray["token"]) && $dataArray["token"] != "") {
					$model->setToken($dataArray["token"]);
				}

				if(isset($dataArray["permissions"]) && $dataArray["permissions"] != "") {
					$pModel = new Permissions();
					$pModel = $pModel->loadById($this->em, $dataArray["permissions"]);
					$model->setPermissions($pModel);
				}



	}


}

