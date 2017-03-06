<?php

namespace Modules\Tivoli\ViewsLogic\Pages\_Public;

use ViewsLogic\Pages\_Base\PAGBasePage;

class PAGPublicAdminLogin extends PAGBasePage {


	protected function assignTplVars() {
		parent::assignTplVars();

	}


	protected function setTplFile() {
		$this->tpl = SMARTY_TEMPLATE_MODULES_FOLDER . '/GFStarterKit/Views/tpls/public/admin_login.tpl';

	}

	protected function isPrivate() {
		return false;
	}

}
