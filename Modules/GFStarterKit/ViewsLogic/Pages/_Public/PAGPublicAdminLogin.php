<?php

namespace Modules\GFStarterKit\ViewsLogic\Pages\_Public;



use Modules\GFStarterKit\ViewsLogic\Pages\PAGBasePage;

class PAGPublicAdminLogin extends PAGBasePage {


	protected function assignTplVars() {
		parent::assignTplVars();
		$this->smarty->assign("companyUrl", "https://www.hexenbytes.com");
		$this->smarty->assign("loginActive", "active");
		$this->smarty->assign("registerActive", "");
		if($this->isUserLogged()) {
			$this->smarty->assign("alreaydLogged", true);
		}
	}


	protected function setTplFile() {
		$this->tpl = GF_SMARTY_TEMPLATE_FOLDER . '/public/admin_login.tpl';

	}

	protected function isPrivate() {
		return false;
	}

}
