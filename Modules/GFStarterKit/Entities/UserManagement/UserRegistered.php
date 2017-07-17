<?php

namespace Modules\GFStarterKit\Entities\UserManagement;
use Doctrine\ORM\Mapping as ORM;
use Modules\GFStarterKit\Entities\BasicModel;
use Modules\GFStarterKit\Entities\UserManagement\Abstracts\BaseUserTrait;


/**
 * BaseUser
 *
 * @ORM\Table(name="gf_users")
 * @ORM\Entity
 */
class UserRegistered extends BasicModel {

	use BaseUserTrait;


	public function getPrivileges() {
		$permisos = array();

		foreach ($this->getPermissions()->getValues() as $permiso) {
			$permisos[] = $permiso->getValue();
		}

		$permisos[]="userregistered_read";
		$permisos[]="userregistered_read_loadall";

		return $permisos;

	}


}
