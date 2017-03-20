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


	/***
	 * Hashes provided password with Bcrypt
	 * @param string $password
	 * @param string $password
	 * @return string
	 */

	public function getHash($password)
	{
		return password_hash($password, PASSWORD_BCRYPT, ['cost' => $this->config['bcrypt_cost']]);
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
	* Checks if an email is already in use
	* @param string $email
	* @return boolean
	*/

	public function isEmailTaken($email)
	{
		$query = $this->dbh->prepare("SELECT * FROM {$this->config['table_users']} WHERE email = ?");
		$query->execute(array($email));

		if ($query->rowCount() == 0) {
			return false;
		}

		return true;
	}

	public function isUserTaken($user)
	{

		$query = $this->dbh->prepare("SELECT * FROM {$this->config['table_users']} WHERE user = ?");
		$query->execute(array($user));

		if ($query->rowCount() == 0) {
			return false;
		}

		return true;
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



	/**
	* Verifies that a password is valid and respects security requirements
	* @param string $password
	* @return array $return
	*/
	private function validatePassword($password) {
		$return = array();
		$return['error'] = true;

		if (strlen($password) < (int)$this->config['verify_password_min_length'] ) {
			//$return['message'] = $this->lang["password_short"];
			return $return;
		}

		$return['error'] = false;
		return $return;
	}


	/**
	 * Verifies that an email is valid
	 * @param string $email
	 * @return array $return
	 */
	private function validateEmail($email) {
		$return = array();
		$return['error'] = true;

		if (strlen($email) < (int)$this->config['verify_email_min_length'] ) {
			//$return['message'] = $this->lang["email_short"];
			return $return;
		} elseif (strlen($email) > (int)$this->config['verify_email_max_length'] ) {
			//$return['message'] = $this->lang["email_long"];
			return $return;
		} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			//$return['message'] = $this->lang["email_invalid"];
			return $return;
		}

		$return['error'] = false;
		return $return;
	}



	/**
	* Informs if a user is locked out
	* @return string
	*/
	public function isBlocked()
	{
		$ip = $this->getIp();
		$this->deleteAttempts($ip, false);
		$query = $this->dbh->prepare("SELECT count(*) FROM {$this->config['table_attempts']} WHERE ip = ?");
    	$query->execute(array($ip));

        $attempts = $query->fetchColumn();

        if($attempts < intval($this->config['attempts_before_block']))
        {
            return false;
        }
     	return true;
	}


	/**
	* Adds an attempt to database
	* @return boolean
	*/

	private function addAttempt()
	{
		$ip = $this->getIp();

		$attempt_expiredate = date("Y-m-d H:i:s", strtotime($this->config['attack_mitigation_time']));

        $query = $this->dbh->prepare("INSERT INTO {$this->config['table_attempts']} (ip, expiredate) VALUES (?, ?)");
        return $query->execute(array($ip, $attempt_expiredate));

	}




}

?>
