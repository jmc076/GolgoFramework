<?php
namespace Modules\GFStarterKit\ViewsLogic\Pages\_Private\dashboard;


class PAGPrivateAdministracionInicio extends PAGPrivateAdministracionBase {


	protected function assignTplVars() {
		parent::assignTplVars();
		$this->smarty->assign("inicioActive", "active");
		$this->smarty->assign("headerTitle", "Administración Inicio");


	}


	protected function setTplFile() {
		print_r("admin home"); die(); //TODO: Diego pre
		$this->tpl = GF_SMARTY_TEMPLATE_FOLDER . '/private/dashboard/admin_home.tpl';

	}


}
