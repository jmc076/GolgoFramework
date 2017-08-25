<?php

namespace Modules\GFStarterKit\EntitiesLogic;

use Modules\GFStarterKit\Utils\AssignGenerator;
use Modules\GFStarterKit\Controllers\PermissionsController;
use Modules\GFStarterKit\GFDoctrineManager;
use Modules\GFStarterKit\Controllers\UserController;
use Modules\GFStarterKit\Entities\UserManagement\UserRegistered;
use Exception;
use Core\Controllers\GFSessions\GFSessionController;
use Core\Controllers\Http\Request;
use Core\Controllers\GFEvents\GFEventController;
use Core\Controllers\ExceptionController;
use Core\Controllers\Http\Decorators\RequestJSONDecorator;
use Core\Controllers\JWTController;
use Core\Helpers\Utils;
use Core\Controllers\CacheController;

class LogicCRUD implements CRUDInterface {

	protected $session;
	protected $em;
	protected $request;

	protected $userModel;
	protected $result;
	protected $checkCSRF;


	protected $routeParams = array();
	protected $dataArray = array();

	public static $cacheKey;


	public function __construct() {
		GFEventController::dispatch("LogicCRUD.__construct", null);

		$this->session = GFSessionController::getInstance();
		$this->request = \GFStarter::$request;
		$this->em = GFDoctrineManager::getEntityManager();
		$this->checkCSRF = $this->request->getMatchedRoute()->isCSRFProtected;

		$this->routeParams = $this->request->getRouteParams();
		
		if(count($this->request->getGetParams()) > 0) {
			$this->dataArray = $this->request->getGetParams();
		}
		if(count($this->request->getPostParams()) > 0) {
			$this->dataArray = $this->request->getPostParams();
		}
		$op = isset($this->dataArray['op']) ? $this->dataArray['op'] : null;
		if($op == null) {
			$this->getOPFromVerb($op);
			$this->dataArray['op'] = $op;
		}

		$this->manageAuthHeaders();
		$this->userModel = UserController::getCurrentUserModel();

		self::$cacheKey = "api:" . $this->session->getSessionId() . ":" . md5(serialize($this->dataArray));

		$this->preload();

		if($this->checkCSRF) {
			if(!$this->session->isValidCSRF($this->dataArray)) {
				ExceptionController::invalidCSRF();
			};
		}
		switch ($op) {
			case "create":
			    $this->dataArray = array_filter($this->dataArray);
				$this->result = $this->create($this->dataArray);
				break;
			case "read":
				$this->result = $this->read($this->dataArray);
				break;
			case "update":
				$this->result = $this->update($this->dataArray);
				break;
			case "delete":
				$this->result = $this->delete($this->dataArray);
				break;
			default:
				ExceptionController::noOPFound();
				break;
		}
		GFEventController::dispatch(get_class($this)."_".$op, null);
		$this->request->getResponse()->setResponseBody($this->result);
		$responseJSon = new RequestJSONDecorator($this->request);
		$responseJSon->setJSONResponse();

	}

	function getOPFromVerb(&$op) {
		$method = $this->request->getMethod();

		switch ($method) {
			case 'PUT':
				$op = "update";
				break;
			case 'POST':
				$op = "create";
				break;
			case 'GET':
				$op = "read";
				break;
			case 'HEAD':
				$op = "read";
				break;
			case 'DELETE':
				$op = "delete";
				break;
			case 'OPTIONS':
				$op = "read";
				break;
			default:
				$op = "read";
				break;
		}
	}

	function preload() {
		GFEventController::dispatch("LogicCRUD.preload", null);

	}

	function manageAuthHeaders() {
		$username = null;
		$password = null;

		if (isset($_SERVER['PHP_AUTH_USER'])) {
			$username = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];
		} elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
			if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']),'basic') === 0)
				list($username,$password) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
		}

		$authHeader = $this->request->getHeaderLine('authorization');
		if ($authHeader) {
			list($jwt) = sscanf( $authHeader, 'Bearer %s');
			if ($jwt) {
				$token = JWTController::decodeToken($jwt);
				if($token !== false) {
					$model = new UserRegistered();
					$this->userModel = $model->loadByToken($token->data->token);
				}
			}
		} else if (!is_null($username) && !is_null($password)) {
			$userController = new UserController();
			$result = $userController->login($username, $password);
			if($result["error"] == false) {
				$userModel =  $result["user_model"];
				$userModel->setToken(Utils::getRandomKey());
				$userModel->persistNow();
				$sessionModel = $this->session->getSessionModel();
				$sessionModel->setStatus(true)->setUserId($userModel->getId())->setUserModel($userModel->getModelNameWithNamespace());
				$jwt = new JWTController();
				$jwt->initializeToken(array("token"=>$userModel->getToken()));

			} else {
				ExceptionController::customError("Datos de acceso incorrectos", 404);
			}

		}
	}


	public function create($dataArray) {
		$return = false;
		if($this->checkPrivileges($dataArray)) {
			try {
				$model = $this->getEntity();
				$this->assignParams($dataArray,$model);
				$this->em->persist($model);
				$this->em->flush();
				$return = $model->getId();
			} catch (Exception $e) {
				$return = false;
			}
		} else {
			ExceptionController::PermissionDenied();
		}
		return $return;
	}


	public function update($dataArray) {
		$return = false;
		if($this->checkPrivileges($dataArray)) {
			if(isset($dataArray["id"])) {
				$model = $this->getEntity();
				$model->loadById($this->em, $dataArray["id"]);
				$this->assignParams($dataArray,$model);
				$this->em->persist($model);
				$this->em->flush();
				$return = $model->getId();
			} else {
				ExceptionController::customError("Missing Entity ID", 400);
			}
		} else {
			ExceptionController::PermissionDenied();
		}
		return $return;

	}
	public function read($dataArray) {
		$return = false;
		if($this->checkPrivileges($dataArray)) {
			if(isset($dataArray["id"])) {
				$model = $this->getEntity();
				$model = $model->loadById($this->em, $dataArray["id"],true);
				return $model;
			} else {
				ExceptionController::customError("Missing Entity ID", 400);
			}
		} else {
			ExceptionController::PermissionDenied();
		}
		return $return;
	}

	public function delete($dataArray) {
		$return = false;
		if($this->checkPrivileges($dataArray)) {
			if(isset($dataArray["id"])) {
				$model = $this->getEntity();
				$model->loadById($this->em, $dataArray["id"]);
				$this->em->remove($model);
				$this->em->flush();
				$return =  true;
			} else {
				ExceptionController::customError("Missing Entity ID", 400);
			}
		} else {
			ExceptionController::PermissionDenied();
		}
		return $return;
	}


	public function getFromCache() {
		if(REDIS_CACHE_ENABLED) {
			return CacheController::get()->getFromCache(self::$cacheKey);
		} else {
			return null;
		}

	}

	public function saveToCache($result) {
		if(REDIS_CACHE_ENABLED) {
			return CacheController::get()->getFromCache(self::$cacheKey);
		} else {
			return null;
		}

	}
	/**
	 * {@inheritDoc}
	 * @see \Modules\GFStarterKit\EntitiesLogic\CRUDInterface::isPrivate()
	 */
	function isPrivate() {
		return true;

	}

	/**
	 * {@inheritDoc}
	 * @see \Modules\GFStarterKit\EntitiesLogic\CRUDInterface::getEntity()
	 */
	function getEntity() {
		return end(explode("/", $this->request->getUri()->getPath()));

	}

	/**
	 * {@inheritDoc}
	 * @see \Modules\GFStarterKit\EntitiesLogic\CRUDInterface::assignParams()
	 */
	public function assignParams($dataArray, &$model) {
		AssignGenerator::generarAsignacion($model, $dataArray);

	}

	public function checkPrivileges($dataArray) {
		if($this->isPrivate() == false || $this->isSuperAdmin()) return true;

		return PermissionsController::checkPermisos($dataArray, $this);
	}


	public function isSuperAdmin() {
		return $this->userModel->getUserType() == USER_SUPERADMIN;
	}

	public function isAdmin() {
		return ($this->userModel->getUserType() == USER_ADMIN || $this->userModel->getUserType() == USER_SUPERADMIN);
	}

}

