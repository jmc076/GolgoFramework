<?php

namespace Modules\GFStarterKit\ViewsLogic\Pages\_Public;



use Modules\GFStarterKit\ViewsLogic\Pages\PAGBasePage;

class PAGPublicAdminRegister extends PAGBasePage {


	protected function assignTplVars() {
		parent::assignTplVars();
		$this->smarty->assign("companyUrl", "https://www.hexenbytes.com");
		$this->smarty->assign("loginActive", "");
		$this->smarty->assign("registerActive", "active");
	}


	protected function setTplFile() {
		$this->tpl = GF_SMARTY_TEMPLATE_FOLDER . '/public/admin_register.tpl';

	}

	protected function isPrivate() {
		return false;
	}

}
