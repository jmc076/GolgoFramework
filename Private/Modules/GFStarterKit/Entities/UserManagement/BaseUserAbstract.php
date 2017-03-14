<?php

namespace Modules\UserManagement\Entities;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Modules\GFStarterKit\Entities\BasicModel;

/**
 * BaseUser
 *
 */
abstract class BaseUserAbstract extends BasicModel
{

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    protected $nombre;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="user", type="string", length=255, nullable=true)
     */
    protected $user;

    /**
     * @var string $nombre
     *
     * @ORM\Column(name="tipo_usuario", type="string", length=255, nullable=false)
     */
    protected $tipoUsuario;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var string $email
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    protected $telefono;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     */
    protected $password;

    /**
     * @var string $isActive
     *
     * @ORM\Column(name="isactive", type="integer")
     */
    protected $isActive;

    /**
     * @var string $dateCreated
     *
     * @ORM\Column(name="date_created", type="datetime")
     */
    protected $dateCreated;

    /**
     * @var string $email
     *
     * @ORM\Column(name="last_ip", type="string", length=255, nullable=true)
     */
    protected $lastIp;

    /**
     * @var string $dateCreated
     *
     * @ORM\Column(name="last_login", type="datetime")
     */
    protected $lastLogin;

    /**
     * @var string $email
     *
     * @ORM\Column(name="session_id", type="string", length=255, nullable=true)
     */
    protected $sessionId;

    /*
     * protected $lastIp;
     * protected $lastLogin;
     * */


    /**
     * @ORM\ManyToMany(targetEntity="Modules\UserManagement\Entities\Permissions", cascade={"persist"})
     * @ORM\JoinTable(name="um_users2permissions",
     *      joinColumns={@ORM\JoinColumn(name="id_user", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id_permission", referencedColumnName="id", unique=true)}
     *      )
     **/
    protected $permissions;


    public function __construct() {
    	$this->permissions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function callback($params) {
    	print_r("callback called"); die(); //TODO: Diego pre
    }

    public function setLastLogin($disc)
    {
    	$this->lastLogin= $disc;
    }
    public function getLastLogin()
    {
    	return $this->lastLogin;
    }

    public function setLastIp($disc)
    {
    	$this->lastIp= $disc;
    }
    public function getLastIp()
    {
    	return $this->lastIp;
    }

    public function setSessionId($disc)
    {
    	$this->sessionId= $disc;
    }
    public function getSessionId()
    {
    	return $this->sessionId;
    }

    public function setTipoUsuario($disc)
    {
    	$this->tipoUsuario= $disc;
    }
    public function getTipoUsuario()
    {
    	return $this->tipoUsuario;
    }

    public function setTelefono($disc)
    {
    	$this->telefono= $disc;
    }
    public function getTelefono()
    {
    	return $this->telefono;
    }


    public function setPermissions($perm)
    {
    	$this->permissions[] = $perm;
    }
    public function getPermissions()
    {
    	return $this->permissions;
    }

    public function setIsActive($tipo)
    {
    	$this->isActive = $tipo;
    }
    public function getIsActive()
    {
    	return $this->isActive;
    }

    public function setDateCreated($tipo)
    {
    	$this->dateCreated = $tipo;
    }
    public function getDateCreated()
    {
    	return $this->dateCreated;
    }



    public function setUser($user)
    {
    	$this->user = $user;
    }
    public function getUser()
    {
    	return $this->user;
    }
    /**
     * Set nombre
     *
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    /**
     * Get nombre
     *
     * @ORM\return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }
    /**
     * Get password
     *
     * @ORM\return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Set telefono
     *
     * @param string $telefono
     */

    public function getPermisos() {
    	return array();
    }








}
