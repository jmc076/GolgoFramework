<?php
namespace Controllers\Interfaces;



interface SessionControllerInterface {
	
	public function initSession();
	
	public static function getIp();
	
	public static function getSessionUserId();
	
	public static function regenerateSession();
	
	public static function getSessionId();
	
	public static function setSessionData($key,$value);
	
	public static function getSessionData($key);
	
	public static function exitSession();
	
	public static function initSessionTimings();

	public static function getCurrentUserModel();
	
	public static function get_token_id();
	
	public static function get_token();
	
	public static function check_valid($dataArray, $exception);
}
