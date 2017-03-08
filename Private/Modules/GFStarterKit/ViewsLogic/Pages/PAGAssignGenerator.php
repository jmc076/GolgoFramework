<?php
namespace Modules\GFStarterKit\ViewsLogic\Pages;



use Modules\GFStarterKit\Utils\AssignGenerator;

class PAGAssignGenerator extends PAGBasePage{

	protected function assignTplVars() {
		$this->smarty->assign("generado", AssignGenerator::generarAsignacion(/*CLASS HERE*/));
		//UNCOMMENT THIS LINE AND ADD CLASS
		//$this->smarty->assign("formulario", AssignGenerator::generateForm(/*CLASS HERE*/, null, array("hasLabel","hasFormGroupExtraDiv")));
	}

	protected function setTplFile() {
		$this->tpl = SMARTY_TEMPLATE_MODULES_FOLDER . '/public/assigngenerator.tpl';

	}

	protected function isPrivate() {
		return false;
	}

}
