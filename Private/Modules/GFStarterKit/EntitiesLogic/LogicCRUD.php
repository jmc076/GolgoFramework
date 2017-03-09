<?php

namespace Modules\GFStarterKit\EntitiesLogic;

use Controllers\ExceptionController;
use Controllers\Http\Response;
use Controllers\Http\Request;
use Controllers\Http\Decorators\ResponseJSONDecorator;
use Controllers\RedisCacheController;
use Controllers\GFEvents\GFEventController;
use Controllers\GFSessions\GFSessionController;
use Modules\GFStarterKit\GFSKEntityManager;

abstract class LogicCRUD {

	protected $userModel;
	protected $result;
	protected $em;
	protected $checkCSRF;
	protected $gfSession;

	protected $modelId;
	protected $response;
	protected $request;
	protected $routeParams;
	protected $dataArray;

	protected $redisClient;

	public function __construct(Request $request, Response $response) {
		GFEventController::dispatch("LogicCRUD.__construct", null);

		$this->gfSession = GFSessionController::getInstance();

		if(REDIS_CACHE_ENABLED) {
		     $this->redisClient = RedisCacheController::getRedisClient();
		}

		$this->em = GFSKEntityManager::getEntityManager();
		$this->response = $response;
		$this->request = $request;

		if($this->request != null) {
			$this->routeParams = $this->request->getUrlRouteParams();


			if(isset($this->routeParams["modelId"])) {
				$this->modelId = $this->routeParams["modelId"];
			}


			if(count($this->request->getGetParams()) > 0) {
				$this->dataArray = $this->request->getGetParams();
			}
			if(count($this->request->getPostParams()) > 0) {
				$this->dataArray = $this->request->getPostParams();
			}

			$op = isset($this->dataArray['op']) ? $this->dataArray['op'] : "read";
			$this->checkCSRF = $this->request->getNeedCheckCSRF() ? true : false;


			$this->preload();

			if($this->checkCSRF && $this->request->getVerb() == "POST" ) {
				//SessionController::check_valid($this->dataArray);
			}

			if($op != null) {
			    $key = "api:".$this->gfSession->getSessionId(). ":" . md5(serialize($this->dataArray));
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
				if($this->response != null) {
					GFEventController::dispatch(get_class($this)."_".$op, array("data" => $this->dataArray, "result" => $this->result));
					$this->response->setBody($this->result);
					$responseJSon = new ResponseJSONDecorator($this->response);
					$responseJSon->dispatchJSONResponse();
				} else {
					return $this->result;
				}
			} else {
				ExceptionController::noOPFound();
			}
		}
	}


	protected function preload() {
		$username = null;
		$password = null;

		if (isset($_SERVER['PHP_AUTH_USER'])) {
			$username = $_SERVER['PHP_AUTH_USER'];
			$password = $_SERVER['PHP_AUTH_PW'];
		} elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
			if (strpos(strtolower($_SERVER['HTTP_AUTHORIZATION']),'basic') === 0)
				list($username,$password) = explode(':',base64_decode(substr($_SERVER['HTTP_AUTHORIZATION'], 6)));
		}


		if (!is_null($username) && !is_null($password)) {
			$user = new BaseUser();
			$loged = $this->auth->login($username, $password);
			if(!$loged["error"]) {
				$this->response->setStatusCode(200);
				$model = $user->loadById($this->em, $loged['id']);
				if($model != null) {
					//SessionController::setSessionData('user_model', $model->getEntityWithNamespace($model));
					//SessionController::setSessionData('tipo_usuario', $model->getTipoUsuario());
					//SessionController::setSessionData('user_id', $model->getId());
					//SessionController::setSessionData('user_name', $model->getNombre());
					//SessionController::setSessionData('user_email', $model->getEmail());
					//SessionController::setSessionData('status', true);
					//SessionController::regenerateSession();
				} else {
					ExceptionController::customError("Datos de acceso incorrectos", 404);
				}
			} else {
				ExceptionController::customError("Datos de acceso incorrectos2", 404);
			}
			//$this->userModel = SessionController::getCurrentUserModel();
			GFEventController::dispatch("LogicCRUD.preload.HTTP_AUTHORIZATION", array("user" => $username,"pass" => $password));
		} else {
			//$this->userModel = SessionController::getCurrentUserModel();
			GFEventController::dispatch("LogicCRUD.preload", null);
		}

	}



	public function deletePublicFile($ruta) {
		if(file_exists(ROOT_PATH . '/Public' . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR.$ruta)) {
			return unlink(ROOT_PATH . '/Public' . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR.$ruta);
		}
		return false;
	}

	public function deletePrivateFile($ruta) {
		if(file_exists( ROOT_PATH . '/Private' . DIRECTORY_SEPARATOR . 'Files' . DIRECTORY_SEPARATOR . $ruta)) {
			return unlink( ROOT_PATH . '/Private' . DIRECTORY_SEPARATOR . 'Files'.DIRECTORY_SEPARATOR . $ruta);
		}
		return false;
	}

	public function create($dataArray) {
		$return = false;
		if($this->hasPermissions($dataArray)) {
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
		if($this->hasPermissions($dataArray)) {
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
		if($this->hasPermissions($dataArray)) {
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
		if($this->hasPermissions($dataArray)) {
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

	protected function hasPermissions($dataArray) {
		return !$this->needPermissions();
	}
	protected function needPermissions() {
		return false;
	}
	protected  function getEntity(){}
	protected  function assignParams($dataArray, &$model){}

}

