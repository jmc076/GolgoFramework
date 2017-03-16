<?php

namespace Modules\GFStarterKit\Entities\UserManagement;


use Modules\GFStarterKit\Entities\UserManagement\Abstracts\UserInterface;

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
