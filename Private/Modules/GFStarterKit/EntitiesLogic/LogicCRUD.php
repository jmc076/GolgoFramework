<?php

namespace Modules\GFStarterKit\EntitiesLogic;

use Controllers\ExceptionController;
use Controllers\Http\Request;
use Controllers\RedisCacheController;
use Controllers\GFEvents\GFEventController;
use Controllers\GFSessions\GFSessionController;
use Modules\GFStarterKit\Utils\AssignGenerator;
use Controllers\GFSessions\CSRFSessionController;
use Controllers\Http\Decorators\RequestJSONDecorator;
use Modules\GFStarterKit\Entities\UserManagement\UserRegistered;
use Modules\GFStarterKit\Controllers\PermissionsController;
use Modules\GFStarterKit\GFDoctrineManager;
use Modules\GFStarterKit\Controllers\UserController;

class LogicCRUD implements CRUDInterface {

	protected $userModel;
	protected $result;
	protected $checkCSRF;


	protected $routeParams = array();
	protected $dataArray = array();

	protected $session;
	protected $redisClient;
	protected $em;
	protected $request;

	public function __construct() {
		GFEventController::dispatch("LogicCRUD.__construct", null);

		$this->session = GFSessionController::getInstance();
		$this->request = Request::getInstance();
		$this->em = GFDoctrineManager::getEntityManager();

		$this->routeParams = $this->request->getUrlRouteParams();

		if(count($this->request->getGetParams()) > 0) {
			$this->dataArray = $this->request->getGetParams();
		}
		if(count($this->request->getPostParams()) > 0) {
			$this->dataArray = $this->request->getPostParams();
		}
		$op = isset($this->dataArray['op']) ? $this->dataArray['op'] : null;
		if($op == null) {
			$this->getOPFromVerb($op);
		}
		$this->checkCSRF = $this->request->getNeedCheckCSRF() ? true : false;
		if(REDIS_CACHE_ENABLED) {
			$this->redisClient = RedisCacheController::getRedisClient();
		}

		$this->preload();

		if($this->checkCSRF) {
			$csrf = CSRFSessionController::getInstance();
			if(!$csrf->isValid($this->dataArray)) {
				ExceptionController::invalidCSRF();
			};
		}
		if($op != null) {
			$key = "api:" . $this->session->getSessionId() . ":" . md5(serialize($this->dataArray));
		    if(REDIS_CACHE_ENABLED) {
		        if($this->redisClient->exists($key)) {
		            $this->result = json_decode($this->redisClient->get($key));
		        }
		    }
			if($this->result == null) {
    			switch ($op) {
    				case "create":
    					$this->result = $this->create($this->dataArray);
    					break;
    				case "read":
    					$this->result = $this->read($this->dataArray);
    					if(REDIS_CACHE_ENABLED) {
    						   $this->redisClient->setex($key, 100 ,json_encode($this->result));
    					}
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
			}
			$this->request->setResponseBody($this->result);
			GFEventController::dispatch(get_class($this)."_".$op, null);
			$responseJSon = new RequestJSONDecorator($this->request);
			$responseJSon->sendJSONResponse();

		} else {
			ExceptionController::noOPFound();
		}
	}

	function getOPFromVerb(&$op) {
		$method = $this->request->getVerb();

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
				break;
			case 'DELETE':
				$op = "delete";
				break;
			case 'OPTIONS':
				break;
			default:
				break;
		}
	}

	function preload() {
		$this->manageHeadersAuth();
		$this->userModel = UserController::getCurrentUserModel();
		GFEventController::dispatch("LogicCRUD.preload", null);

	}

	function manageHeadersAuth() {
		$username = null;
		$password = null;

		if (isset($_SERVER['PHP_AUTH_USER'])) {
			$username = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];
		} elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
			if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']),'basic') === 0)
				list($username,$password) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
		}

		/*$authHeader = $this->request->getHeaderAsString('authorization');
		print_r($authHeader); die(); //TODO: Diego pre
		if ($authHeader) {
			list($jwt) = sscanf( $authHeader->toString(), 'Authorization: Bearer %s');
			if ($jwt) {
				$token = JWT::decode($jwt, $secretKey, array('HS512'));
			}
		}*/


		if (!is_null($username) && !is_null($password)) {


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


	/**
	 * {@inheritDoc}
	 * @see \Modules\GFStarterKit\EntitiesLogic\CRUDInterface::isPrivate()
	 */
	function isPrivate() {
		return true;

	}

	function needPrivileges() {
		return true;
	}

	/**
	 * {@inheritDoc}
	 * @see \Modules\GFStarterKit\EntitiesLogic\CRUDInterface::getEntity()
	 */
	function getEntity() {
		return end(explode("/", $this->request->getRequestUrl()));

	}

	/**
	 * {@inheritDoc}
	 * @see \Modules\GFStarterKit\EntitiesLogic\CRUDInterface::assignParams()
	 */
	public function assignParams($dataArray, &$model) {
		AssignGenerator::generarAsignacion($model, $dataArray);

	}

	public function checkPrivileges($dataArray) {
		if($this->needPrivileges() == false || $this->isSuperAdmin()) return true;

		return PermissionsController::checkPermisos($dataArray, $this);
	}


	public function isSuperAdmin() {
		return $this->userModel->getUserType() == USER_SUPERADMIN;
	}

	public function isAdmin() {
		return $this->userModel->getUserType() == USER_ADMIN;
	}

}

