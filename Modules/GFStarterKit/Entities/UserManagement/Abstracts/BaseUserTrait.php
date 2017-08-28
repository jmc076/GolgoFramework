<?php

namespace Modules\GFStarterKit\Entities\UserManagement\Abstracts;

use Doctrine\ORM\NoResultException;
use Modules\GFStarterKit\GFDoctrineManager;
use Exception;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\AccessType;
use JMS\Serializer\Annotation\PostDeserialize;
use Core\Controllers\ExceptionController;

/**
 * BaseUser
 * @AccessType("public_method")
 */
trait BaseUserTrait {

    /**
     * @var name
     * @Type("string")
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @var firstName
     * @Type("string")
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * @var lastName
     * @Type("string")
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $lastName;

    /**
     * @var email
     * @Type("string")
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var bio
     * @Type("string")
     * @ORM\Column(name="bio", type="string", nullable=true)
     */
    protected $bio;

    /**
     * @var telephone
     * @Type("string")
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    protected $telephone;

    /**
     * @var userName
     * @Type("string")
     * @ORM\Column(name="username", type="string", length=255, nullable=true)
     */
    protected $userName;

    /**
     * @var password
     * @Type("string")
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Exclude
     */
    private $password;

    /**
     * @var userType
     * @Type("string")
     * @ORM\Column(name="user_type", type="string", length=255, nullable=false)
     */
    protected $userType;

    /**
     * @var isActive
     * @Type("int")
     * @ORM\Column(name="is_active", type="integer")
     */
    protected $isActive;

    /**
     * @var dateCreated
     * @Type("DateTime")
     * @ORM\Column(name="date_created", type="datetime")
     */
    protected $dateCreated;

    /**
     * @var lastIp
     * @Type("string")
     * @ORM\Column(name="last_ip", type="string", length=255, nullable=true)
     */
    protected $lastIp;

    /**
     * @var lastLogin
     * @Type("DateTime")
     * @ORM\Column(name="last_login", type="datetime")
     */
    protected $lastLogin;

    /**
     * @var sessionId
     * @Type("string")
     * @ORM\Column(name="session_id", type="string", length=255, nullable=true)
     */
    protected $sessionId;

    /**
     * @var activationKey
     * @Type("string")
     * @ORM\Column(name="activation_key", type="string", length=255, nullable=true)
     */
    protected $activationKey;

    /**
     * @var userAvatar
     * @Type("string")
     * @ORM\Column(name="user_avatar", type="string", length=255, nullable=true)
     */
    protected $userAvatar;

    /**
     * @var shouldChangePassword
     * @Type("boolean")
     * @ORM\Column(name="should_change_password", type="integer")
     */
    protected $shouldChangePassword;

    /**
     * @var token
     * @Type("string")
     * @ORM\Column(name="token", type="string", length=255, nullable=true)
     */
    protected $token;

    /**
     * @var pushToken
     * @Type("string")
     * @ORM\Column(name="push_token", type="string", nullable=true)
     */
    protected $pushToken;

    /**
     * @var permissions
     * @Type("ArrayCollection<Modules\GFStarterKit\Entities\UserManagement\Permissions>")
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
        print_r("callback called"); die(); // TODO: Diego pre
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

    public function getPushToken() {
        return $this->pushToken;
    }
    public function setPushToken($pushToken) {
        $this->pushToken = $pushToken;
        return $this;
    }


    /**
     * @PostDeserialize
     */
    public function validateFormObject($op) {
        $msg = "";
        $code = 400;
        switch ($op) {
            case "create":
                if($this->activationKey == "") $msg = "Empty activationKey";
                if($this->userName == "") $msg = "Empty userName";
                if($this->userType == "") $msg = "Empty userType";
                if($this->password == "") $msg = "Empty password";
                if($this->name == "") $msg = "Empty name";
                if($this->email == "") $msg = "Empty email";
                break;
        }
        if($msg != "") {
            ExceptionController::customError($msg, $code);
        } else {
            return true;
        }
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
