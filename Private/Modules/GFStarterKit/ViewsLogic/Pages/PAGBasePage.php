<?php
namespace ViewsLogic\Pages\_Base;

use Controllers\Http\Response;

use Controllers\Http\Request;
use Controllers\i18nController;
use Controllers\SessionController;

require 'Private/Vendors/Smarty-3.1.21/libs/Smarty.class.php';

class PAGBasePage {

	protected $smarty;
	protected $tpl;
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
		$this->em = $GLOBALS['em'];

		if(isset($this->routeParams["modelId"])) {
			$this->modelId = $this->routeParams["modelId"];
		}

		$this->getParams = $this->request->getGetParams();
		$this->postParams = $this->request->getPostParams();
		$this->init();
	}

	protected function init() {
		if($this->isPrivate() == true && (!isset($_SESSION["sessionData"]) || $_SESSION["sessionData"]["status"] == false)) {
			header("Location:/");
		} else {
			$this->preLoad();
			$this->smarty = new \Smarty();
			$this->assignTplVars();
			$this->setTplFile();
			$this->displayTpl();

		}

	}

	protected function preLoad(){
		$this->userModel = SessionController::getCurrentUserModel();
	}

	public function isSuperAdmin() {
		return $this->userModel->getTipoUsuario() == USER_SUPERADMINISTRADOR;
	}

	public function isAdmin() {
		return $this->userModel->getTipoUsuario() == USER_ADMINISTRADOR;
	}

	protected function assignTplVars() {
		if(isset($_SESSION["errorMSG"])) {
			$this->smarty->assign("errorMSG", $_SESSION["errorMSG"]);
			$_SESSION["errorMSG"] = "";
			unset($_SESSION["errorMSG"]);
		}
		$this->smarty->assign("csrfdata", '<input id="csrf" type="hidden" name="'.SessionController::get_token_id().'" value="'.SessionController::get_token().'" />');
		if(NEED_LOCALIZATION) {
			$this->smarty->assign("i18n", i18nController::localization());
		}
	}

	protected function setTplFile() {

	}

	protected function displayTpl() {
		header('Content-type: text/html; charset=UTF-8');
		$this->smarty->display($this->tpl);
	}

	protected function getHtmlOutput() {
		return $this->smarty->fetch($this->tpl);
	}

	protected function isPrivate() {
		return false;
	}



}