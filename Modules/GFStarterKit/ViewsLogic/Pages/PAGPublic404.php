<?php

namespace Modules\GFStarterKit\ViewsLogic\Pages;

class PAGPublic404 extends PAGBasePage {


	protected function assignTplVars() {
		parent::assignTplVars();

	}


	protected function setTplFile() {
		$this->tpl = GF_SMARTY_TEMPLATE_FOLDER . '/public/404.tpl';

	}

	protected function isPrivate() {
		return false;
	}

}
