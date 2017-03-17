<?php
namespace Modules\UserManagement\EntitiesLogic;

use Modules\UserManagement\Requires\AuthClass;
use Modules\UserManagement\Controllers\PermissionsController;
use EntitiesLogic\Base\LogicCRUD;

class UserManagementLogicCRUD extends LogicCRUD {

	protected $auth;
	protected function preload() {
		$this->permisos = new PermissionsController();
		$this->auth = new AuthClass();
		parent::preload();
	}

	protected function needPermissions() {
		return true;
	}

	public function hasPermissions($dataArray) {
		if($this->isSuperAdmin() ||  $this->isAdmin()) {
			return true;
		}
		return !$this->needPermissions() || $this->permisos->checkPermisos($dataArray,$this);
	}

	public function isSuperAdmin() {
		return $this->userModel->getTipoUsuario() == USER_SUPERADMINISTRADOR;
	}

	public function isAdmin() {
		return $this->userModel->getTipoUsuario() == USER_ADMINISTRADOR;
	}

}