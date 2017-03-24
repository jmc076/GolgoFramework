<?php
namespace Modules\UserManagement\EntitiesLogic;

use BaseEntities\Serializor;
use Controllers\ExceptionController;
use Controllers\FileController;
use Controllers\SessionController;
use Modules\GFStarterKit\Entities\UserManagement\UserRegistered;
use Modules\GFStarterKit\EntitiesLogic\LogicCRUD;
use Modules\UserManagement\Entities\Permissions;
use Controllers\GFSessions\GFSessionController;


class BaseUserLogic extends LogicCRUD {



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
				    //NO USAR EMAIL
					if(isset($dataArray["email"]))
						$loged = $this->auth->login($dataArray["email"], $dataArray["pass"]);
					else
						$loged = $this->auth->login($dataArray["user"], $dataArray["pass"]);
					if(!$loged["error"]) {
						$return = true;
						$this->response->setStatusCode(200);
						$model = $model->loadById($this->em, $loged['id']);
						if($model != null) {
							SessionController::setSessionData('user_model', $model->getEntityWithNamespace($model));
							SessionController::setSessionData('tipo_usuario', $model->getTipoUsuario());
							SessionController::setSessionData('user_id', $model->getId());
							SessionController::setSessionData('user_name', $model->getNombre());
							SessionController::setSessionData('user_email', $model->getEmail());
							SessionController::setSessionData('status', true);
							SessionController::regenerateSession();
						} else {
							ExceptionController::customError("Datos de acceso incorrectos", 404);
							$return = false;
						}
					} else {
						ExceptionController::customError("Datos de acceso incorrectos2", 404);
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
		if($this->hasPermissions($dataArray)) {
			if((isset($dataArray["email"]) && $this->auth->isEmailTaken($dataArray["email"]))
			 || (isset($dataArray["user"]) && $this->auth->isUserTaken($dataArray["user"]))) {
				$error = array('error' => "El email o usuario ya está en uso.");
				return $error;
			}
			$model = $this->getEntity();
			try {
				$this->assignParams($dataArray,$model);
				$this->em->persist($model);
				$this->em->flush();
				$return = $model->getId();
			} catch (Exception $e) {
				print_r($e->getMessage()); die(); //TODO: Diego pre
				$return = false;
			}

		} else {
			ExceptionController::PermissionDenied();
		}

		return $return;
	}

	public function update($dataArray) {
		$return = false;
		if($this->hasPermissions($dataArray)) {
			$model = $this->getEntity();
			if(isset($dataArray["id"])) $model = $model->loadById($this->em, $dataArray["id"]);
			else $model = $this->userModel;

			if(isset($dataArray["email"]) && $model->getEmail() != $dataArray["email"] && $this->auth->isEmailTaken($dataArray["email"])) {
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
		if($this->hasPermissions($dataArray)) {
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

	protected function assignParams($dataArray, &$model) {

		if(isset($dataArray['op']) && $dataArray['op'] == 'update' && isset($dataArray["files"]) && isset($dataArray["files"]["avatar"]) && $dataArray["files"]["avatar"]["error"] == 0) {
			if($model->getAvatar() != null && $model->getAvatar() != "") {
				$this->deletePublicFile($model->getAvatar());
			}
			$ruta = FileController::putFilesPublicNew($dataArray["files"]["avatar"], "img/userAvatars", $model->getId());
			$this->tempFiles[] = $ruta[0];
			$model->setAvatar($ruta[1]);
		}

		if(isset($dataArray["tipoUsuario"]) && !empty($dataArray["tipoUsuario"]) &&  $dataArray["tipoUsuario"] != $model->getTipoUsuario()) {
			$model->setTipoUsuario($dataArray["tipoUsuario"]);
		} else if(!isset($dataArray["tipoUsuario"])) {
			$model->setTipoUsuario(USER_USER);
		}

		if(isset($dataArray["isAdmin"]) && $dataArray["isAdmin"] == 1) {
			$model->setTipoUsuario(USER_ADMINISTRADOR);
		}


		if(isset($dataArray["email"]) && $dataArray["email"] != "") {
			$model->setEmail($dataArray["email"]);
		}

		if(isset($dataArray["user"]) && $dataArray["user"] != "") {
			$model->setUser($dataArray["user"]);
		}

		if(isset($dataArray["categoria"]) && $dataArray["categoria"] != "") {
			$model->setCategoria($dataArray["categoria"]);
		}

		if(isset($dataArray["pass"]) && $dataArray["pass"] != "") {
			$model->setPassword($this->auth->getHash($dataArray["pass"]));
		}

		if(isset($dataArray["nombre"]) && $dataArray["nombre"] != "")
			$model->setNombre(utf8_decode($dataArray["nombre"]));

		if(isset($dataArray["telefono"]) && $dataArray["telefono"] != "")
			$model->setTelefono($dataArray["telefono"]);

		if(isset($dataArray["movil"]) && $dataArray["movil"] != "")
			$model->setMovil($dataArray["movil"]);

		if(isset($dataArray["nif"]) && $dataArray["nif"] != "")
			$model->setDni($dataArray["nif"]);

		if(isset($dataArray["codigo-postal"]) && $dataArray["codigo-postal"] != "")
			$model->setCp($dataArray["codigo-postal"]);

		if(isset($dataArray["direccion"]) && $dataArray["direccion"] != "")
			$model->setDireccion(utf8_decode($dataArray["direccion"]));

		if(isset($dataArray["web"]) && $dataArray["web"] != "")
			$model->setWeb($dataArray["web"]);

		if(isset($dataArray["descripcion"]) && $dataArray["descripcion"] != "")
			$model->setDescripcion(utf8_decode($dataArray["descripcion"]));


		if($this->isAdmin() || $this->isSuperAdmin() && isset($dataArray["containsPermissions"])) {
		    $this->assignPermisos($dataArray,$model);
		}

	}

	private function assignPermisos($dataArray, &$model) {
	    $permisos = $model->getPermissions();
	    if(isset($dataArray["inmuebleCreate"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 1);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 1);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }
	    if(isset($dataArray["inmuebleRead"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 2);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 2);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }

	    if(isset($dataArray["inmuebleUpdate"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 3);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 3);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }

	    if(isset($dataArray["inmuebleDelete"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 4);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 4);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }
	    ////////////
	    if(isset($dataArray["captacionesCreate"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 5);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 5);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }
	    if(isset($dataArray["captacionesRead"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 6);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 6);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }

	    if(isset($dataArray["captacionesUpdate"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 7);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 7);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }

	    if(isset($dataArray["captacionesDelete"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 8);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 8);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }
	    ///////////////////
	    if(isset($dataArray["demandasCreate"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 9);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 9);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }
	    if(isset($dataArray["demandasRead"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 10);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 10);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }

	    if(isset($dataArray["demandasUpdate"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 11);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 11);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }

	    if(isset($dataArray["demandasDelete"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 12);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 12);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }
	    ////////////////////////////////////
	    if(isset($dataArray["clientesCreate"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em,13);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 13);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }
	    if(isset($dataArray["clientesRead"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 14);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 14);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }

	    if(isset($dataArray["clientesUpdate"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 15);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 15);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }

	    if(isset($dataArray["clientesDelete"])) {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em, 16);
	        if($permisos && !$permisos->contains($perm))
	            $model->setPermissions($perm);
	    } else {
	        $perm = new Permissions();
	        $perm = $perm->loadById($this->em,16);
	        if($permisos && $permisos->contains($perm))
	            $model->getPermissions()->removeElement($perm);
	    }
	}

}