<?php

namespace Modules\GFStarterKit\Entities\UserManagement;
use Doctrine\ORM\Mapping as ORM;


/**
 * BaseUser
 *
 * @ORM\Table(name="gf_users")
 * @ORM\Entity
 */
class UserBasic implements UserInterface
{
	use BaseUserTrait;


	/**
	 * {@inheritDoc}
	 * @see \Modules\GFStarterKit\Entities\UserManagement\UserInterface::getPermisos()
	 */
	public function getPermisos() {
		// TODO: Auto-generated method stub

	}

	/**
	 * {@inheritDoc}
	 * @see \Modules\GFStarterKit\Entities\UserManagement\UserInterface::getUserType()
	 */
	public function getUserType() {
		// TODO: Auto-generated method stub

	}

}
