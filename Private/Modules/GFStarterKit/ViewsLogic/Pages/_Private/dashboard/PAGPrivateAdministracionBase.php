<?php

namespace Modules\GFStarterKit\ViewsLogic\Pages\_Private\dashboard;


use Modules\GFStarterKit\ViewsLogic\Pages\PAGBasePage;
use Controllers\GFSessions\GFSessionController;

class PAGPrivateAdministracionBase extends PAGBasePage{

	public function preLoad() {
		print_r("admin base"); die(); //TODO: Diego pre
		parent::preLoad();
		if(!$this->isAdmin() || !$this->isSuperAdmin()) {
			GFSessionController::getInstance()->exitSession();
			header("Location:/login");
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
