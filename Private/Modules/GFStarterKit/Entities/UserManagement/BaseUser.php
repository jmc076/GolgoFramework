<?php

namespace Modules\UserManagement\Entities;
use Doctrine\ORM\Mapping as ORM;
use Exception;
use Doctrine\Common\Collections\ArrayCollection;
use BaseEntities\BasicModel;

/**
 * BaseUser
 *
 * @ORM\Table(name="um_users")
 * @ORM\Entity
 */
class BaseUser extends BaseUserAbstract
{

}
