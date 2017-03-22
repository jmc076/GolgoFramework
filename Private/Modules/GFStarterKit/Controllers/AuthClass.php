<?php

namespace Modules\UserManagement\Requires;
/*
* AuthClass
* Works with PHP 5.4 and above.
*/

class AuthClass
{
	public $dbh;
	public $config;
	//public $lang;

	public function __construct()
	{
		$this->dbh = new \PDO("mysql:host=".MYSQL_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
		$this->config = array(
        		'bcrypt_cost' => '10',
        		'table_attempts' => 'um_attempts',
        		'table_users' => TABLE_USERS,
        		'attack_mitigation_time' => '+30 minutes',
        		'attempts_before_block' => '5',
        		'verify_email_max_length' => '100',
        		'verify_email_min_length' => '5',
        		'verify_password_min_length' => '3',

        );
		//$this->lang = $GLOBALS['lang'];

		if (version_compare(phpversion(), '5.5.0', '<')) {
			require("password.php");
		}
	}

	/*
	* Logs a user in
	* @param string $email
	* @param string $password
	* @param bool $remember
	* @return array $return
	*/

	public function login($user, $password)
	{
		$return = array();
		$return['error'] = true;


		$block_status = $this->isBlocked();
		if ($block_status) {
			//$return['message'] = $this->lang["user_blocked"];
			return $return;
		}

		$uid = $this->getUID($user);

		if(!$uid) {
			//$return['message'] = $this->lang["email_password_incorrect"];
			$this->addAttempt();
			return $return;
		}

		$user = $this->getUser($uid);

		if (!password_verify($password, $user['password'])) {
			//$return['message'] = $this->lang["email_password_incorrect"];
			$this->addAttempt();
			return $return;
		}

		if ($user['isactive'] != 1) {
			//$return['message'] = $this->lang["account_inactive"];
			return $return;
		}


		$return['id'] = $user['uid'];
		$return['error'] = false;
		//$return['message'] = $this->lang["logged_in"];


		return $return;
	}



	/*
	* Gets UID for a given email address and returns an array
	* @param string $email
	* @return array $uid
	*/

	public function getUID($user)
	{
		$query = $this->dbh->prepare("SELECT id FROM {$this->config['table_users']} WHERE user = ?");
		$query->execute(array($user));

		if($query->rowCount() == 0) {
			return false;
		}

		return $query->fetch(\PDO::FETCH_ASSOC)['id'];
	}





	/*
	* Gets user data for a given UID and returns an array
	* @param int $uid
	* @return array $data
	*/

	public function getUser($uid)
	{
		$query = $this->dbh->prepare("SELECT email, password, isactive FROM {$this->config['table_users']} WHERE id = ?");
		$query->execute(array($uid));

		if ($query->rowCount() == 0) {
			return false;
		}

		$data = $query->fetch(\PDO::FETCH_ASSOC);

		if (!$data) {
			return false;
		}

		$data['uid'] = $uid;
		return $data;
	}












}

?>
