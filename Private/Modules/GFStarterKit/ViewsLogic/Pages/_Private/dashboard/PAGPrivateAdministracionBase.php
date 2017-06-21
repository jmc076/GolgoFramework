<?php

namespace Modules\GFStarterKit\ViewsLogic\Pages\_Private\dashboard;


use Modules\GFStarterKit\ViewsLogic\Pages\PAGBasePage;
use Controllers\GFSessions\GFSessionController;

class PAGPrivateAdministracionBase extends PAGBasePage {

	protected function preLoad() {
		parent::preLoad();

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

	}


	protected function isPrivate() {
		return true;
	}

}
