<?php
namespace Modules\GFStarterKit\EntitiesLogic\UserManagementLogic;

use Controllers\ExceptionController;
use Controllers\FileController;
use Modules\GFStarterKit\Controllers\UserController;
use Modules\GFStarterKit\Entities\UserManagement\UserRegistered;
use Modules\GFStarterKit\EntitiesLogic\LogicCRUD;


class BaseUserLogic extends LogicCRUD {

	protected $userController;


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

	public function logout() {
		$this->gfSession->exitSession();
		header("Location: /");
		die();
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
							$userModel =  $result["user_model"]->getId();
							$sessionModel = $this->gfSession->getSessionModel();
							$sessionModel->setStatus(true)->setUserId($userModel->getId());
						} else {
							if($result["message"] == ERROR_USER_NAME_NOT_FOUND) {
								$dataArray["isAdmin"] = true;
								return $this->create($dataArray);
							} else
								ExceptionController::customError("Login failed with code: " . $result["message"], 400);
						}
					} else {
						ExceptionController::customError("missing password and user in form", 400);
					}

					break;
				case "loadAll":
					if($this->checkPrivileges($dataArray) || $this->userModel->getTipoUsuario() == USER_ADMINISTRADOR) {
						$models = $this->getEntity();
						$models = $models->loadAll($this->em, null);
						$return = $models;
					} else {
						ExceptionController::PermissionDenied();
					}
					break;
				case "loadById":
					if($this->checkPrivileges($dataArray) || $this->userModel->getTipoUsuario() == USER_ADMINISTRADOR) {
						$model = $model->loadById($this->em, $dataArray["id"],true);
						$return = $model;
					} else {
						ExceptionController::PermissionDenied();
					}
					break;
				case "loadAdmins":
					if($this->checkPrivileges($dataArray) || $this->userModel->getTipoUsuario() == USER_ADMINISTRADOR) {
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


		if(isset($dataArray["tipoUsuario"]) && !empty($dataArray["tipoUsuario"]) &&  $dataArray["tipoUsuario"] != $model->getTipoUsuario()) {
			$model->setUserType($dataArray["tipoUsuario"]);
		} else if(!isset($dataArray["tipoUsuario"])) {
			$model->setUserType(USER_REGISTERED);
		}

		if(isset($dataArray["isAdmin"]) && $dataArray["isAdmin"] == 1) {
			$model->setUserType(USER_ADMIN);
		}


		if(isset($dataArray["email"]) && $dataArray["email"] != "") {
			$model->setEmail($dataArray["email"]);
		}
		if(isset($dataArray["user"]) && $dataArray["user"] != "") {
			$model->setUserName($dataArray["user"]);
		}
		if(isset($dataArray["password"]) && $dataArray["password"] != "") {
			$model->setPassword($this->userController->getHash($dataArray["password"]));
		}

		$model->setIsActive(0);



	}


}

