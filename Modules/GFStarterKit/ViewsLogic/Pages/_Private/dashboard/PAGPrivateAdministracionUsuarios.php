<?php

namespace Modules\GFStarterKit\ViewsLogic\Pages\_Private\dashboard;

class PAGPrivateAdministracionUsuarios extends PAGPrivateAdministracionBase {


	protected function assignTplVars() {
		parent::assignTplVars();
		$this->smarty->assign("usuariosActive", "active");
		$this->smarty->assign("headerTitle", "Gestión de Usuarios");
	}


	protected function setTplFile() {
		$this->tpl = GF_SMARTY_TEMPLATE_FOLDER . '/private/dashboard/usuarios.tpl';

	}


}
