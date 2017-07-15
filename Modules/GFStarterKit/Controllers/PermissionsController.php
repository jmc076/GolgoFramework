<?php
namespace Modules\GFStarterKit\Controllers;


use Modules\GFStarterKit\Entities\UserManagement\UserRegistered;
use Modules\GFStarterKit\GFDoctrineManager;

class PermissionsController {

	private static $permArray = array (
	);


	/**
	 * add permission to the permissions array
	 * @param string $perm
	 * @return void
	 */
	public static function addPerm(String $perm) {
		if (!array_key_exists($perm, self::$permArray)) {
			self::$permArray[] = $perm;
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


	public static function checkPermisos($params, $instance) {

		$op = $params['op'];
		$classname = $instance->getEntity();
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


		$user = UserController::getCurrentUserModel();
		$userType = $user->getUserType();
		if($userType == USER_ADMIN || $userType == USER_SUPERADMIN) return true;

		$allowedPermisos = $user->getPrivileges();

		if (in_array($opPerm, $allowedPermisos) && in_array($perm, $allowedPermisos)){
			return true;
		} else {
			print_r($perm);
			print_r("<br>");
			print_r($opPerm); die(); //TODO: Diego pre
			return false;
		}
	}
	public static function checkPermisosRoute($route, $userModel) {
		$perm = strtolower($route);

		$allowedPermisos = $userModel->getPrivileges();

		if (in_array($perm, $allowedPermisos)){
			return true;
		} else {
			return false;
		}
	}


}