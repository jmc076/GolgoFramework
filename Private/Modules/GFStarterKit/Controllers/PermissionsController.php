<?php
namespace Modules\UserManagement\Controllers;


class PermissionsController {

	private static $permArray = array (
			BASEUSER_CREATE,
			BASEUSER_READ,
			BASEUSER_UPDATE,
			BASEUSER_DELETE,
			BASEUSER_READ_DOLOGIN,
			BASEUSER_UPDATE_UPDATEUSER,
			BASEUSER_CREATE_CREATEADMIN
	);


	/**
	 * add permission to the permissions array
	 * @param string $perm
	 * @return void
	 */
	public function addPerm(String $perm) {
		if (!array_key_exists($perm, self::$permArray)) {
			$this->permArray[] = $perm;
		}
		return true;

	}

	public static function isPermSet(String $perm){
		return array_key_exists($perm, self::$permArray);
	}

	/**
	 * The array with static permissions
	 * @return array
	 */
	public static function getPerms() {
		return self::$permArray;
	}


	public function checkPermisos($params, $model) {

		$op = $params['op'];
		$classname = $model->getEntity();
		$classname = $classname->getModelName();
		$perm = $classname."_".$op;
		$perm = strtolower($perm);
		$perm = ltrim($perm, '\\');
		$opPerm = $perm;

		if(isset($params["sop"]) && $params["sop"] != "") {
			$perm .= "_" . $params["sop"];
			$perm = strtolower($perm);
			$perm = ltrim($perm, '\\');
		}



		$user = SessionController::getCurrentUserModel();

		$allowedPermisos = $user->getPermisos();


		if (in_array($opPerm, $allowedPermisos) && in_array($perm, $allowedPermisos)){
			return true;
		} else {
		//	print_r($perm); die(); //TODO: Diego pre
			return false;
		}
	}


}