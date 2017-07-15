<?php

namespace Modules\GFStarterKit\Entities\UserManagement;
use Doctrine\ORM\Mapping as ORM;
use Modules\GFStarterKit\Entities\BasicModel;



/**
 * Permissions
 *
 * @ORM\Table(name="gf_permissions")
 * @ORM\Entity
 */
class Permissions extends BasicModel
{

    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=255, nullable=false)
     */
    protected $value;



    /**
     * Set value
     *
     * @param string $value
     * @return Pemissions
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

}
