<?php
namespace Modules\GFStarterKit\ViewsLogic\Pages;


use Controllers\Http\Request;
use Controllers\i18nController;
use Controllers\GFSessions\GFSessionController;
use Modules\GFStarterKit\GFEntityManager;


class PAGBasePage {

	protected $smarty;
	protected $tpl;
	protected $getParams;
	protected $postParams;
	protected $em;
	protected $modelId;
	protected $userModel;
	protected $request;
	protected $routeParams;
	protected $session;

	public function __construct() {
		$this->request = Request::getInstance();
		$this->session = GFSessionController::getInstance();
		$this->init();
	}

	protected function init() {
		if($this->isPrivate() == true && (!isset($_SESSION["sessionData"]) || $_SESSION["sessionData"]["status"] == false)) {
			header("Location:/");
		} else {

			$this->routeParams = $this->request->getUrlRouteParams();
			$this->em = GFEntityManager::getEntityManager();

			if(isset($this->routeParams["modelId"])) {
				$this->modelId = $this->routeParams["modelId"];
			}

			$this->getParams = $this->request->getGetParams();
			$this->postParams = $this->request->getPostParams();

			$this->preLoad();
			$this->smarty = new \Smarty();
			$this->smarty
			->setCompileDir(ROOT_PATH .'/Private/Modules/GFStarterKit/templates_c')
			->setCacheDir(ROOT_PATH .'/Private/Modules/GFStarterKit/cache');
			$this->smarty->setCaching(true);
			$this->assignTplVars();
			$this->setTplFile();
			$this->displayTpl();

		}

	}

	protected function preLoad(){
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
		$this->smarty->assign("csrfdata", '<input id="csrf" type="hidden" name="'.$this->session->getSessionCsrfName().'" value="'.$this->session->getSessionCsrfValue().'" />');
		if(NEED_LOCALIZATION) {
			$this->smarty->assign("i18n", i18nController::localization());
		}
	}

	protected function setTplFile() {

	}

	protected function displayTpl() {
		$this->request->setHeader("Content-type", "text/html; charset=UTF-8");
		$this->request->setResponseBody($this->smarty->fetch($this->tpl));
	}

	protected function simpleDisplayTpl() {
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
