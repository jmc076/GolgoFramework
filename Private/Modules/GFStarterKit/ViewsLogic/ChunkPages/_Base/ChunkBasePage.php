<?php
namespace ViewsLogic\ChunkPages\_Base;
use Controllers\ExceptionController;
use Controllers\i18nController;
use Controllers\SessionController;
use Controllers\Http\Request;
use Controllers\Http\Response;
use Modules\GFStarterKit\GFSKEntityManager;

require 'Private/Vendors/Smarty-3.1.21/libs/Smarty.class.php';
class ChunkBasePage {

	protected $getParams;
	protected $postParams;
	protected $em;
	protected $modelId;
	protected $userModel;
	protected $response;
	protected $request;
	protected $routeParams;


	public function __construct(Request $request, Response $response) {
		$this->response = $response;
		$this->request = $request;
		$this->routeParams = $this->request->getUrlRouteParams();
		$this->em = GFSKEntityManager::getEntityManager();

		if(isset($this->routeParams["modelId"])) {
			$this->modelId = $this->routeParams["modelId"];
		}

		$this->getParams = $this->request->getGetParams();
		$this->postParams = $this->request->getPostParams();
		$this->init();
	}

	protected function init() {
		$this->preLoad();
		if($this->isPrivate() == true && (!isset($_SESSION["sessionData"]) || $_SESSION["sessionData"]["status"] == false)) {
			ExceptionController::PermissionDenied();
		} else if($this->requireAdmin() == true && $this->userModel->getTipoUsuario() != USER_ADMINISTRADOR){
			ExceptionController::PermissionDenied();
		} else {
			$this->smarty = new \Smarty();
			$this->assignTplVars();
			$this->setTplFile();
			$this->response->setBody($this->getHtmlOutput());
			$this->response->sendResponse();
		}

	}

	protected function preLoad(){
		$this->userModel = SessionController::getCurrentUserModel();

	}


	protected function assignTplVars() {
		$this->smarty->assign("csrfdata", '<input id="csrf" type="hidden" name="'.SessionController::get_token_id().'" value="'.SessionController::get_token().'" />');
		if(NEED_LOCALIZATION) {
			$this->smarty->assign("i18n", i18nController::localization());
		}
	}

	protected function setTplFile() {
	}

	protected function getHtmlOutput() {
		return $this->smarty->fetch($this->tpl);
	}

	protected function isPrivate() {
		return true;
	}

	protected function requireAdmin() {
		return false;
	}


}
