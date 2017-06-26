<?php
namespace Modules\GFStarterKit\ViewsLogic\Pages;



use Modules\GFStarterKit\Utils\AssignGenerator;
use Modules\GFStarterKit\Entities\UserManagement\UserRegistered;
use Modules\GFStarterKit\Utils\AssignCodeGenerator;

class PAGAssignGenerator extends PAGBasePage{

	protected function assignTplVars() {
		$this->smarty->assign("generado", AssignCodeGenerator::generarAsignacion(new UserRegistered(), null));
		//UNCOMMENT THIS LINE AND ADD CLASS
		//$this->smarty->assign("formulario", AssignGenerator::generateForm(/*CLASS HERE*/, null, array("hasLabel","hasFormGroupExtraDiv")));
	}

	protected function setTplFile() {
		$this->tpl = GF_SMARTY_TEMPLATE_FOLDER . '/public/assigngenerator.tpl';

	}

	protected function isPrivate() {
		return false;
	}

}
