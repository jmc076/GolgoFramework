<?php
namespace Modules\GFStarterKit;

use Core\Controllers\Router\RouteCollection;
use Core\Controllers\GFEvents\GFEventController;
use Core\Controllers\Router\RouteModel;

require_once 'vendor/autoload.php';




define('GF_SMARTY_TEMPLATE_FOLDER', 'Modules/GFStarterKit/Views/tpls');
define("GF_TABLE_USERS", "gf_users");
define("GF_TABLE_ATTEMPTS","gf_login_attempts");

define("USER_SUPERADMIN", "super_admin");
define("USER_ADMIN", "admin");
define("USER_REGISTERED", "user_registered");

define('LOGIN_ATTEMPTS_MITIGATION_TIME', '+1 minutes');
define('LOGIN_ATTEMPTS_BEFORE_BLOCK', '5');
define('EMAIL_MAX_LENGTH','100');
define('EMAIL_MIN_LENGTH','5');
define('PASSWORD_MIN_LENGTH','5');
define('PASSWORD_BCRYPT_COST','10');

define('ERROR_USER_BLOCKED', "user_blocked");
define('ERROR_USER_NAME_NOT_FOUND', "username_not_found");
define('ERROR_USER_PASSWORD_MISSMATCH', "user_password_missmatch");
define('ERROR_USER_NOT_ACTIVE', "user_not_active");

define("GF_JWT_AUTHENTICATION_EXPIRATION", 60*60); //1 hour



class Bootstrap {


	function __construct(RouteCollection $routerCollection) {

		if(file_exists( __DIR__ .'/vendor/autoload.php'))
			require_once __DIR__ .'/vendor/autoload.php';

		GFDoctrineManager::getEntityManager();

		$this->setRoutes($routerCollection);
		$this->startEventListeners();

	}

	private function startEventListeners() {
		$callback = function($params) { };
		GFEventController::on("Router.dispatchWithMatch", $callback);
	}

	private function setRoutes(RouteCollection $routerCollection) {

		$baseNamespace = "Modules\GFStarterKit";


		$config = array();
		$config["name"] = "";
		$config["checkCSRF"] = false;

		/**
		 * PAGE ROUTES
		 */
		$config["targetClass"] = $baseNamespace."\ViewsLogic\Pages\_Public\PAGPublicAdminLogin";
		$route = RouteModel::withConfig("/login", $config);
		$routerCollection->attachRoute($route);
		$route = RouteModel::withConfig("/acceso", $config);
		$routerCollection->attachRoute($route);
		$route = RouteModel::withConfig("/", $config);
		$routerCollection->attachRoute($route);

		$config["targetClass"] = $baseNamespace."\ViewsLogic\Pages\_Public\PAGPublicAdminRegister";
		$route = RouteModel::withConfig("/register", $config);
		$routerCollection->attachRoute($route);

		$config["targetClass"] = $baseNamespace."\ViewsLogic\Pages\_Private\dashboard\PAGPrivateAdministracionInicio";
		$route = RouteModel::withConfig("/dashboard", $config);
		$routerCollection->attachRoute($route);
		$route = RouteModel::withConfig("/dashboard/home", $config);
		$routerCollection->attachRoute($route);

		$config["targetClass"] = $baseNamespace."\ViewsLogic\Pages\_Private\dashboard\PAGPrivateAdministracionUsuarios";
		$route =RouteModel::withConfig("/dashboard/users", $config);
		$routerCollection->attachRoute($route);

		$config["targetClass"] = $baseNamespace."\ViewsLogic\Pages\_Private\dashboard\PAGPrivateAdministracionLockscreen";
		$route = RouteModel::withConfig("/lockscreen", $config);
		$routerCollection->attachRoute($route);

		$config["targetClass"] = $baseNamespace . "\Controllers\UserController";
		$config["targetClassMethod"] = "logout";
		$route = RouteModel::withConfig("/logout", $config);
		$routerCollection->attachRoute($route);
		$route =RouteModel::withConfig("/exit", $config);
		$routerCollection->attachRoute($route);
		unset($config["targetClassMethod"]);

		/**
		 * CHUNKS
		 */

		$config["targetClass"] = $baseNamespace."\ViewsLogic\ChunkPages\ChunkPrivateUsuarios";
		$route = RouteModel::withConfig("/chunks/usuarios", $config);
		$routerCollection->attachRoute($route);
		$route = RouteModel::withConfig("/chunks/usuarios/:id", $config);
		$routerCollection->attachRoute($route);


		/**
		 * API ROUTES
		 */
		$config["targetClass"] = $baseNamespace."\EntitiesLogic\UserManagementLogic\BaseUserLogic";
		$route = RouteModel::withConfig("/api/Users", $config);
		$routerCollection->attachRoute($route);



	}

}
