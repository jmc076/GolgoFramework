<?php

namespace Modules\GFStarterKit\ViewsLogic\Pages\_Private\dashboard;


use Modules\GFStarterKit\ViewsLogic\Pages\PAGBasePage;
use Controllers\GFSessions\GFSessionController;

class PAGPrivateAdministracionBase extends PAGBasePage {

	protected function preLoad() {
		$this->request->setHeader("Cache-Control","no-cache, no-store, must-revalidate");
		parent::preLoad();
		if(strpos($_SERVER['REQUEST_URI'], "lockscreen") === false){
			$this->session->getSession()->put("previousUrl", $_SERVER['REQUEST_URI']);
		}
		if(!$this->isAdmin() && !$this->isSuperAdmin()) {
			GFSessionController::getInstance()->exitSession();
			header("Location: /".BASE_PATH_DIRECTORY."/login");
			die();
		}
		if(GFSessionController::getInstance()->getSession()->get("lockedScreen") && !$this instanceof PAGPrivateAdministracionLockscreen ) {
			header("Location: /".BASE_PATH_DIRECTORY."/lockscreen");
			die();
		}
	}


	protected function assignTplVars() {
		parent::assignTplVars();
		$this->smarty->assign("inicioActive", "");
		$this->smarty->assign("usuariosActive", "");

	}


	protected function isPrivate() {
		return true;
	}

}
