<?php

namespace Modules\GFStarterKit\Entities\UserManagement;
use Doctrine\ORM\Mapping as ORM;
use Modules\GFStarterKit\Entities\UserManagement\Abstracts\UserInterface;
use Modules\GFStarterKit\Entities\UserManagement\Abstracts\BaseUserTrait;
use Modules\GFStarterKit\Entities\BasicModel;


/**
 * BaseUser
 *
 * @ORM\Table(name="gf_users")
 * @ORM\Entity
 */
class UserRegistered extends BasicModel implements UserInterface
{
	use BaseUserTrait;


	/**
	 * {@inheritDoc}
	 * @see \Modules\GFStarterKit\Entities\UserManagement\UserInterface::getPermisos()
	 */
	public function getPrivileges() {
		$permisos = array();

		foreach ($this->getPermissions()->getValues() as $permiso) {
			$permisos[] = $permiso->getValue();
		}

		return $permisos;

	}


}
