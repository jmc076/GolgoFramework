<?php
namespace Modules\GFStarterKit\ViewsLogic\Pages;


use Controllers\Http\Request;
use Controllers\i18nController;
use Controllers\GFSessions\GFSessionController;
use Modules\GFStarterKit\GFDoctrineManager;
use Modules\GFStarterKit\Controllers\UserController;


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

	protected $userController;


	protected $sessionModel;

	public function __construct() {
		$this->request = Request::getInstance();
		$this->session = GFSessionController::getInstance();
		$this->userController = new UserController();
		$this->init();
	}

	protected function init() {
		$this->sessionModel = $this->session->getSessionModel();
		if($this->isPrivate() == true && !$this->isUserLogged()) {
			$this->redirectTo("/".BASE_PATH_DIRECTORY);
		} else {
			$this->routeParams = $this->request->getUrlRouteParams();
			$this->em = GFDoctrineManager::getEntityManager();

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
			$this->smarty->setCaching(false);
			$this->assignTplVars();
			$this->setTplFile();
			$this->displayTpl();

		}

	}

	protected function preLoad(){
		$this->userModel = UserController::getCurrentUserModel();
	}

	public function isSuperAdmin() {
		return $this->userModel->getUserType() == USER_SUPERADMIN;
	}

	public function isAdmin() {
		return $this->userModel->getUserType() == USER_ADMIN;
	}

	protected function assignTplVars() {
		$this->smarty->assign("basePath", BASE_PATH);
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


	public function redirectTo($location) {
		$this->request->setHeader("Location", $location);
	}

	public function isUserLogged() {
		return $this->sessionModel->getStatus() === true && $this->sessionModel->getUserId() != 0;
	}


}
