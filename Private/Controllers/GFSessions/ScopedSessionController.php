<?php
namespace Controllers\GFSessions;


use GFModels\GFSessionModel;

class ScopedSessionController implements ScopedSessionInterface {

	use ScopedSessionTrait;


	public function initSessionModel() {
		if(!$this->isKeySet("sessionModel")) {
			$model =  new GFSessionModel();
			$model->initializeValues();
			$this->put("sessionModel", $model);
		}
	}

	public function getSessionModel() {
		return $this->get("sessionModel");
	}

	public function saveSessionModel($model) {
		$this->put("sessionModel", $model);
	}
}
