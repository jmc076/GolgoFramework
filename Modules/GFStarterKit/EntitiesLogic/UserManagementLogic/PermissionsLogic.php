<?php
namespace Modules\GFStarterKit\EntitiesLogic\UserManagementLogic;

use Core\Controllers\ExceptionController;
use Modules\GFStarterKit\EntitiesLogic\LogicCRUD;
use Modules\GFStarterKit\Entities\UserManagement\Permissions;

class PermissionsLogic extends LogicCRUD {


	public function getEntity() {
		return new Permissions();
	}

	public function read($dataArray) {
		$return = null;
		$model = $this->getEntity();
			if(isset($dataArray["sop"])) {
				if($dataArray["sop"] == "loadAll")	{
					$return = $model->loadAll($this->em, $dataArray);
				} elseif ($dataArray["sop"] == "loadById")	{
					$return = $model->loadById($this->em, $dataArray["id"]);
				}
			} else {
				$return = $model->loadAll($this->em, $dataArray);
			}


		return $return;
	}


	public function update($dataArray) {
		return false;

	}

	public function create($dataArray) {

		if($this->hasPermissions($dataArray)) {
			$model = $this->getEntity();
			$model->setValue($dataArray["value"]);
			$this->em->persist($model);
			$this->em->flush();
		} else {
			ExceptionController::PermissionDenied();
		}

	}
}