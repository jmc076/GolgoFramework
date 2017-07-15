<?php

namespace Modules\GFStarterKit\Entities\UserManagement;



class UserAnonym {

	public function getPrivileges() {
		$permisos = array();
		$permisos[] = "userregistered_read_dologin";
		$permisos[] = "userregistered_read";
		return $permisos;
	}

	public function getUserType() {
		return (new \ReflectionClass($this))->getShortName();
	}


	public function getId() {
		return 0;
	}

}
