<?php

namespace Modules\GFStarterKit\ViewsLogic\Pages\_Private\dashboard;

use Controllers\GFSessions\GFSessionController;

class PAGPrivateAdministracionLockscreen extends PAGPrivateAdministracionBase {



	protected function preLoad() {
		parent::preLoad();

		if($this->request->getVerb() ==  "POST") {
			$isValidPass = $this->userController->comparePasswords($this->userModel->getId(), $this->postParams["password"]);
			if($isValidPass) {
				GFSessionController::getInstance()->put("lockedScreen", false);
				$this->redirectTo(GFSessionController::getInstance()->get("previousUrl"));
			}
		} else if($this->request->getVerb() ==  "GET") {
			GFSessionController::getInstance()->put("lockedScreen", true);
			GFSessionController::getInstance()->put("lastUrl", $_SERVER['REQUEST_URI']);
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
