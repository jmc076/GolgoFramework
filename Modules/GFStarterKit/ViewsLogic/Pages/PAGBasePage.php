<?php
namespace Modules\GFStarterKit\ViewsLogic\Pages;


use Modules\GFStarterKit\GFDoctrineManager;
use Modules\GFStarterKit\Controllers\UserController;
use Modules\GFStarterKit\Controllers\PermissionsController;
use Core\Controllers\GFSessions\GFSessionController;
use Core\Controllers\ExceptionController;
use Core\Controllers\i18nController;
use Core\Controllers\Http\Psr\Request;
	

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
			$this->redirectTo("/".DOMAIN_PATH);
		} else if ($this->hasRouteAccess()) {
			$this->em = GFDoctrineManager::getEntityManager();

			$this->routeParams = $this->request->getRouteParams();
			$this->getParams = $this->request->getGetParams();
			$this->postParams = $this->request->getPostParams();

			$this->preLoad();

			$this->smarty = new \Smarty();
			$this->smarty
			->setCompileDir(ROOT_PATH .'/Modules/GFStarterKit/templates_c')
			->setCacheDir(ROOT_PATH .'/Modules/GFStarterKit/cache');
			$this->smarty->setCaching(false);
			$this->assignTplVars();
			$this->setTplFile();
			$this->displayTpl();

		} else {
			if($this->isChunk())
				ExceptionController::routeBlocked();
			else $this->redirectTo("/".DOMAIN_PATH);
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
		$this->smarty->assign("BASE_URL", DOMAIN_HOST . '/' . DOMAIN_PATH);
		$this->smarty->assign("csrfdata", '<input id="csrf" type="hidden" name="'.$this->session->getSessionCsrfName().'" value="'.$this->session->getSessionCsrfValue().'" />');
		if(LOCALIZATION_ENABLED) {
			$this->smarty->assign("i18n", i18nController::localization());
		}
	}

	protected function displayTpl() {
		$this->request->getResponse()->putHeaderValue("Content-type", "text/html; charset=UTF-8");
		$this->request->getResponse()->setResponseBody($this->smarty->fetch($this->tpl));
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
		$this->request->getResponse()->putHeaderValue("Location", $location);
	}

	public function isUserLogged() {
		return $this->sessionModel->getStatus() === true && $this->sessionModel->getUserId() != 0;
	}

	public function isChunk() {
		return false;
	}


}
