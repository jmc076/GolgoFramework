<?php
namespace ViewsLogic\Pages\_Base;
use Entities\AssignGenerator;
use Modules\Companies\Entities\Companies;
use Modules\Companies\Entities\Customers;
use Modules\Companies\Entities\BaseAbstractCustomers;
use Modules\Companies\Entities\Contacts;
use Modules\Tivoli\Entities\Tombolas;
use Modules\Tivoli\Entities\Premios;
use Modules\Tivoli\Entities\PremiosEntregados;
use Modules\Tivoli\Entities\Tickets;



class PAGAssignGenerator extends PAGBasePage{

	protected function assignTplVars() {
		$this->smarty->assign("generado", AssignGenerator::generarAsignacion(new Tickets()));
		$this->smarty->assign("formulario", AssignGenerator::generateForm(new Tickets(), null, array("hasLabel","hasFormGroupExtraDiv")));
	}
	
	protected function setTplFile() {
		$this->tpl = SMARTY_TEMPLATE_FOLDER . '/public/assigngenerator.tpl';

	}

	protected function isPrivate() {
		return false;
	}
	
}
