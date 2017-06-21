<?php

namespace Modules\GFStarterKit\ViewsLogic\Pages\_Private\dashboard;

use Controllers\GFSessions\GFSessionController;

class PAGPrivateAdministracionLockscreen extends PAGPrivateAdministracionBase {



	protected function preLoad() {
		parent::preLoad();
		if($this->request->getVerb() ==  "POST") {
			$isValidPass = $this->userController->comparePasswords($this->userModel->getId(), $this->postParams["password"]);
			if($isValidPass) {
				GFSessionController::getInstance()->getSession()->put("lockedScreen", false);
				header("Location: " . GFSessionController::getInstance()->getSession()->get("previousUrl"));
				die();
			}
		} else if($this->request->getVerb() ==  "GET") {
			GFSessionController::getInstance()->getSession()->put("lockedScreen", true);
			GFSessionController::getInstance()->getSession()->put("lastUrl", $_SERVER['REQUEST_URI']);
		}

	}

	protected function assignTplVars() {
		parent::assignTplVars();
		$this->smarty->assign("userName", $this->userModel->getUserName());
	}


	protected function setTplFile() {
		$this->tpl = GF_SMARTY_TEMPLATE_FOLDER . '/private/dashboard/lockscreen.tpl';

	}



}
