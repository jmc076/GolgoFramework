<?php

namespace Modules\GFStarterKit\FormModels;


use JMS\Serializer\Annotation\Type;

class LoginModel {
	
	/**
	 * @var UserName
	 * @Type("string")
	 */
	protected $user;
	
	/**
	 * @var Password
	 * @Type("string")
	 */
	protected $password;
	
	
	public function toString() {
		return $this->user . "--". $this->password;
	}
	
}