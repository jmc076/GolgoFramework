<?php

namespace Modules\GFStarterKit\ViewsLogic\Pages\_Private\dashboard;


use Modules\GFStarterKit\ViewsLogic\Pages\PAGBasePage;
use Controllers\GFSessions\GFSessionController;

abstract class PAGPrivateAdministracionBase extends PAGBasePage {

	public $userTypes = array(USER_ADMIN, USER_REGISTERED, USER_SUPERADMIN);
	protected $modelId;

	protected function preLoad() {
		if(isset($this->routeParams["modelId"])) {
			$this->modelId = $this->routeParams["modelId"];
		}

		$this->request->setHeader("Cache-Control","no-cache, no-store, must-revalidate");
		if(!$this->isAdmin() && !$this->isSuperAdmin()) {
			GFSessionController::getInstance()->exitSession();
			$this->redirectTo("/".BASE_PATH_DIRECTORY."/login");
		}

		if(GFSessionController::getInstance()->get("lockedScreen") && !$this instanceof PAGPrivateAdministracionLockscreen ) {
			$this->redirectTo("/".BASE_PATH_DIRECTORY."/lockscreen");
		} else {
			if(strpos($_SERVER['REQUEST_URI'], "lockscreen") === false){
				$this->session->put("previousUrl", $_SERVER['REQUEST_URI']);
			}
		}
	}


	protected function assignTplVars() {
		parent::assignTplVars();
		$this->smarty->assign("inicioActive", "");
		$this->smarty->assign("usuariosActive", "");
		$this->smarty->assign("userTypes", $this->userTypes);

	}


	protected function isPrivate() {
		return true;
	}


}
