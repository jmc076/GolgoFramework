<?php
namespace Modules\GFStarterKit\ViewsLogic\Pages;


use Controllers\Http\Request;
use Controllers\i18nController;
use Controllers\GFSessions\GFSessionController;
use Modules\GFStarterKit\GFDoctrineManager;
use Modules\GFStarterKit\Controllers\UserController;
use Modules\GFStarterKit\Controllers\PermissionsController;
use Controllers\ExceptionController;


 abstract class PAGBasePage {

	protected $smarty;
	protected $tpl;
	protected $getParams;
	protected $postParams;
	protected $em;
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
		$this->sessionModel = $this->session->getSessionModel();
		$this->userModel = UserController::getCurrentUserModel();
		$this->init();
	}

	protected function init() {

		if($this->isPrivate() == true && !$this->isUserLogged()) {
			$this->redirectTo("/".BASE_PATH_DIRECTORY);
		} else if ($this->hasRouteAccess()) {
			$this->em = GFDoctrineManager::getEntityManager();

			$this->routeParams = $this->request->getUrlRouteParams();
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

		} else {
			if($this->isChunk())
				ExceptionController::routeBlocked();
			else $this->redirectTo("/".BASE_PATH_DIRECTORY);
		}

	}

	protected function hasRouteAccess() {
		$shouldCheckRouteAccess = $this->shouldCheckRoutePerms();
		if($shouldCheckRouteAccess) {
			$canAccess = PermissionsController::checkPermisosRoute($this->request->getMatchedRoute()->getUrl(), $this->userModel);
			$isAdmin = $this->isAdmin();
			return $isAdmin || $canAccess;
		} else  {
			return true;
		}

	}

	protected function preLoad(){}
	protected abstract function setTplFile();

	protected function assignTplVars() {
		$this->smarty->assign("basePath", BASE_PATH);
		$this->smarty->assign("csrfdata", '<input id="csrf" type="hidden" name="'.$this->session->getSessionCsrfName().'" value="'.$this->session->getSessionCsrfValue().'" />');
		if(LOCALIZATION_ENABLED) {
			$this->smarty->assign("i18n", i18nController::localization());
		}
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

	protected function shouldCheckRoutePerms() {
		return false;
	}

	public function isSuperAdmin() {
		return $this->userModel->getUserType() == USER_SUPERADMIN;
	}

	public function isAdmin() {
		return ($this->userModel->getUserType() == USER_ADMIN || $this->userModel->getUserType() == USER_SUPERADMIN);
	}


	public function redirectTo($location) {
		$this->request->setHeader("Location", $location);
	}

	public function isUserLogged() {
		return $this->sessionModel->getStatus() === true && $this->sessionModel->getUserId() != 0;
	}

	public function isChunk() {
		return false;
	}


}
