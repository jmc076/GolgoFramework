<?php
namespace Modules\GFStarterKit\Entities\UserManagement\Abstracts;


interface UserInterface {

	public function getPrivileges();

	public function getUserType();

}

