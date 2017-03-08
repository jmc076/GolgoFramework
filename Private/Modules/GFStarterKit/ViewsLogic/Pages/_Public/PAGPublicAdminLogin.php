<?php

namespace Modules\GFStarterKit\ViewsLogic\Pages\_Public;



use Modules\GFStarterKit\ViewsLogic\Pages\PAGBasePage;

class PAGPublicAdminLogin extends PAGBasePage {


	protected function assignTplVars() {
		parent::assignTplVars();

	}


	protected function setTplFile() {
		$this->tpl = GF_SMARTY_TEMPLATE_FOLDER . '/public/admin_login.tpl';

	}

	protected function isPrivate() {
		return false;
	}

}
