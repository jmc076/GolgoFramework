<?php
namespace Modules\GFStarterKit\ViewsLogic\ChunkPages;

use Modules\GFStarterKit\Entities\UserManagement\UserRegistered;
use Modules\GFStarterKit\Utils\Serializor;
use Modules\GFStarterKit\ViewsLogic\Pages\_Private\dashboard\PAGPrivateAdministracionBase;

class ChunkPrivateUsuarios extends PAGPrivateAdministracionBase {

	public function isChunk() {
		return true;
	}

	public function shouldCheckRoutePerms(){
		return true;
	}

	protected function assignTplVars() {
		parent::assignTplVars();
		$id = null;
		if(isset($this->getParams["id"])) {
			$id = $this->getParams["id"];
		} else if(isset($this->routeParams["id"])) {
			$id = $this->routeParams["id"];
		}
		if($id != null) {
			$this->smarty->assign("usuario", $this->loadUsuario($id));
			$this->smarty->assign("chunkTitle", "Editar Usuario");
		} else {
			$this->smarty->assign("chunkTitle", "Nuevo Usuario");
		}

	}

	protected function setTplFile() {
		$this->tpl = GF_SMARTY_TEMPLATE_FOLDER . '/private/chunks/usuarios-chunk.tpl';
	}

	protected function loadUsuario($id) {
		$model = new UserRegistered();
		$model = $model->loadById($this->em, $id);
		$return = Serializor::toArray($model,2,null,null, array("password"));
		return $return;
	}

}
