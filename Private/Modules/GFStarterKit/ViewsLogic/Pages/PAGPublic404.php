<?php

namespace ViewsLogic\Pages\_Base;

class PAGPublic404 extends PAGBasePage {


	protected function assignTplVars() {
		parent::assignTplVars();

	}


	protected function setTplFile() {
		$this->tpl = SMARTY_TEMPLATE_MODULES_FOLDER . '/public/404.tpl';

	}

	protected function isPrivate() {
		return false;
	}

}
