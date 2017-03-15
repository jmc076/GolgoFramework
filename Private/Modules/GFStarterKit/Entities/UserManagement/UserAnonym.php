<?php

namespace Modules\GFStarterKit\Entities\UserManagement;


class UserAnonym implements UserInterface
{

	public function getPermisos() {
		$permisos = array();
		return $permisos;
	}

	public function getUserType() {
		return (new \ReflectionClass($this))->getShortName();
	}


}
