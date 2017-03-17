<?php
namespace Modules\GFStarterKit\Controllers;

use Controllers\GFSessions\GFSessionController;
use Firebase\JWT\JWT;

class JWTAuthentication {

	private $key = "example_key";
	private $token;

	private $tokenId;
	private $issuedAt;
	private $notBefore;
	private $expire;
	private $issuer;

	public function __construct() {

		$this->tokenId    = base64_encode(mcrypt_create_iv(32));
		$this->issuedAt   = time();
		$this->notBefore  = $this->issuedAt + 1;
		$this->expire     = $this->notBefore + GF_JWT_AUTHENTICATION_EXPIRATION;
		$this->issuer 	  = BASE_PATH;

	}

	public function initializeToken(array $data = array()) {

		if(!isset($data["data"])) return false;

		$this->token = [
				'iat'  => isset($data["iat"]) ? $data["iat"] : $this->issuedAt, // Issued at: time when the token was generated
				'aud'  => isset($data["aud"]) ? $data["aud"] : aud(),			// Audience claim, verifies
				'jti'  => isset($data["jti"]) ? $data["jti"] : $this->tokenId,  // Json Token Id: an unique identifier for the token
				'iss'  => isset($data["iss"]) ? $data["iss"] : $this->issuer,   // Issuer
				'nbf'  => isset($data["nbf"]) ? $data["nbf"] : $this->notBefore,// Not before
				'exp'  => isset($data["exp"]) ? $data["exp"] : $this->expire,   // Expire
				'data' => isset($data["data"])									// Custom data
		];

		return true;
	}


	public function encodeToken() {
		$jwt = JWT::encode($this->token, $this->key);
		return $jwt;

	}

	public function decodeToken(string $token) {
		try {
			$decoded = JWT::decode($token, $this->key, array('HS256'));
			if($decoded->aud !== self::aud()) {
				throw new Exception("Invalid user logged in.");
			}
		} catch (\Exception $e) {
			return false;
		}

		return $decoded;
	}

	private static function aud()
	{
		$aud = GFSessionController::getInstance()->getSessionModel()->getUserIp();
		$aud .= @$_SERVER['HTTP_USER_AGENT'];
		$aud .= gethostname();

		return sha1($aud);
	}

}