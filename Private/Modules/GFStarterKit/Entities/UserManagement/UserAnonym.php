<?php

namespace Modules\GFStarterKit\Entities\UserManagement;


use Modules\GFStarterKit\Entities\UserManagement\Abstracts\UserInterface;
use Modules\GFStarterKit\Entities\BasicModel;

class UserAnonym implements UserInterface
{

	public function getPrivileges() {
		$permisos = array();
		return $permisos;
	}

	public function getUserType() {
		return (new \ReflectionClass($this))->getShortName();
	}


}
