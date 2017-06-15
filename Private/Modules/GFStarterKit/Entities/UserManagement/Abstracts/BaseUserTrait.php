<?php

namespace Modules\GFStarterKit\Entities\UserManagement\Abstracts;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Modules\GFStarterKit\Entities\BasicModel;
use Modules\GFStarterKit\GFDoctrineManager;

/**
 * BaseUser
 */
trait BaseUserTrait
{

	/**
	 *
	 * @var string $nombre @ORM\Column(name="name", type="string", length=255, nullable=true)
	 */
	protected $name;

	/**
	 *
	 * @var string $nombre @ORM\Column(name="first_name", type="string", length=255, nullable=true)
	 */
	protected $firstName;

	/**
	 *
	 * @var string $nombre @ORM\Column(name="last_name", type="string", length=255, nullable=true)
	 */
	protected $lastName;

	/**
	 *
	 * @var string $email @ORM\Column(name="email", type="string", length=255, nullable=true)
	 */
	protected $email;

	/**
	 *
	 * @var string $bio @ORM\Column(name="bio", type="string", nullable=true)
	 */
	protected $bio;

	/**
	 *
	 * @var string $email @ORM\Column(name="telephone", type="string", length=255, nullable=true)
	 */
	protected $telephone;

	/**
	 *
	 * @var string $nombre @ORM\Column(name="username", type="string", length=255, nullable=true)
	 */
	protected $userName;

	/**
	 *
	 * @var string $password @ORM\Column(name="password", type="string", length=255, nullable=false)
	 */
	protected $password;

	/**
	 *
	 * @var string $nombre @ORM\Column(name="user_type", type="string", length=255, nullable=false)
	 */
	protected $userType;

	/**
	 *
	 * @var string $isActive @ORM\Column(name="is_active", type="integer")
	 */
	protected $isActive;

	/**
	 *
	 * @var string $dateCreated @ORM\Column(name="date_created", type="datetime")
	 */
	protected $dateCreated;

	/**
	 *
	 * @var string $email @ORM\Column(name="last_ip", type="string", length=255, nullable=true)
	 */
	protected $lastIp;

	/**
	 *
	 * @var string $dateCreated @ORM\Column(name="last_login", type="datetime")
	 */
	protected $lastLogin;

	/**
	 *
	 * @var string $email @ORM\Column(name="session_id", type="string", length=255, nullable=true)
	 */
	protected $sessionId;

	/**
	 *
	 * @var string $email @ORM\Column(name="activation_key", type="string", length=255, nullable=true)
	 */
	protected $activationKey;

	/**
	 *
	 * @var string $email @ORM\Column(name="user_avatar", type="string", length=255, nullable=true)
	 */
	protected $userAvatar;

	/**
	 *
	 * @var string $isActive @ORM\Column(name="should_change_password", type="integer")
	 */
	protected $shouldChangePassword;

	/**
	 *
	 * @var string $nombre @ORM\Column(name="token", type="string", length=255, nullable=true)
	 */
	protected $token;

	/**
	 * @ORM\ManyToMany(targetEntity="Modules\GFStarterKit\Entities\UserManagement\Permissions", cascade={"persist"})
	 * @ORM\JoinTable(name="gf_users2permissions",
	 * joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
	 * inverseJoinColumns={@ORM\JoinColumn(name="permission_id", referencedColumnName="id", unique=true)}
	 * )
	 */
	protected $permissions;
	public function __construct() {
		$this->permissions = new \Doctrine\Common\Collections\ArrayCollection ();
	}
	public function callback($params) {
		print_r ( "callback called" );
		die (); // TODO: Diego pre
	}
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	public function getFirstName() {
		return $this->firstName;
	}
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
		return $this;
	}
	public function getLastName() {
		return $this->lastName;
	}
	public function setLastName($lastName) {
		$this->lastName = $lastName;
		return $this;
	}
	public function getEmail() {
		return $this->email;
	}
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}
	public function getBio() {
		return $this->bio;
	}
	public function setBio($bio) {
		$this->bio = $bio;
		return $this;
	}
	public function getTelephone() {
		return $this->telephone;
	}
	public function setTelephone($telephone) {
		$this->telephone = $telephone;
		return $this;
	}
	public function getUserName() {
		return $this->userName;
	}
	public function setUserName($userName) {
		$this->userName = $userName;
		return $this;
	}
	public function getPassword() {
		return $this->password;
	}
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}
	public function getUserType() {
		return $this->userType;
	}
	public function setUserType($userType) {
		$this->userType = $userType;
		return $this;
	}
	public function getIsActive() {
		return $this->isActive;
	}
	public function setIsActive($isActive) {
		$this->isActive = $isActive;
		return $this;
	}
	public function getDateCreated() {
		return $this->dateCreated;
	}
	public function setDateCreated($dateCreated) {
		$this->dateCreated = $dateCreated;
		return $this;
	}
	public function getLastIp() {
		return $this->lastIp;
	}
	public function setLastIp($lastIp) {
		$this->lastIp = $lastIp;
		return $this;
	}
	public function getLastLogin() {
		return $this->lastLogin;
	}
	public function setLastLogin($lastLogin) {
		$this->lastLogin = $lastLogin;
		return $this;
	}
	public function getSessionId() {
		return $this->sessionId;
	}
	public function setSessionId($sessionId) {
		$this->sessionId = $sessionId;
		return $this;
	}
	public function getActivationKey() {
		return $this->activationKey;
	}
	public function setActivationKey($activationKey) {
		$this->activationKey = $activationKey;
		return $this;
	}
	public function getUserAvatar() {
		return $this->userAvatar;
	}
	public function setUserAvatar($userAvatar) {
		$this->userAvatar = $userAvatar;
		return $this;
	}
	public function getShouldChangePassword() {
		return $this->shouldChangePassword;
	}
	public function setShouldChangePassword($shouldChangePassword) {
		$this->shouldChangePassword = $shouldChangePassword;
		return $this;
	}
	public function getPermissions() {
		return $this->permissions;
	}
	public function setPermissions($permissions) {
		$this->permissions = $permissions;
		return $this;
	}
	public function getToken() {
		return $this->token;
	}
	public function setToken($token) {
		$this->token = $token;
		return $this;
	}
	public function loadByToken($token, $hydrated = false) {
		$model = null;
		try {
			$dql = "SELECT t FROM " . get_class ( $this ) . " t WHERE t.token = '{$token}'";
			$query = GFDoctrineManager::getEntityManager()->createQuery ( $dql );
			if ($hydrated) {
				$model = $query->getOneOrNullResult ( \Doctrine\ORM\Query::HYDRATE_ARRAY );
			} else {
				$model = $query->getOneOrNullResult ();
			}
		} catch ( NoResultException $ex ) {
			$model = null;
		} catch ( Exception $ex ) {
			$model = null;
		}
		return $model;
	}
}
