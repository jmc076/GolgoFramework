<?php
namespace Modules\GFFileManager\EntitiesLogic;

use Core\Controllers\ExceptionController;
use Modules\GFFileManager\Entities\Files;
use Modules\GFStarterKit\EntitiesLogic\LogicCRUD;
use Core\Controllers\FileController;
use Modules\GFFileManager\Bootstrap;


class FilesLogic extends LogicCRUD {



	/**
	 * Returns Entity managed in this logic.
	 * @return Files
	 */
	public function getEntity() {
		return new Files();
	}

	public function read($dataArray) {
		$return = null;
		if(isset($dataArray["sop"]) && $dataArray["sop"] != "") {

			switch ($dataArray["sop"]) {
				case "downloadFile":
					$fileController = new FileController();
					$fileController->sendFile(ROOT_PATH .DS. Bootstrap::$baseNamespace . DS.'Files' . $dataArray["path"]);
					break;
			}
		} else {
			ExceptionController::noSOPFound();
		}


		//$return = $model->loadAll($this->em, $getParams);
		return $return;
	}




}

