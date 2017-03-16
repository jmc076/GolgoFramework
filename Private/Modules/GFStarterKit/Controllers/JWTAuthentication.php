<?php

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
		$this->notBefore  = $this->issuedAt + 10;             //Adding 10 seconds
		$this->expire     = $this->notBefore + GF_JWT_AUTHENTICATION_EXPIRATION;            // Adding 60 seconds
		$this->issuer 	  = BASE_PATH; // Retrieve the server name from config file
	}

	public function initializeTokenWithData(array $data = array()) {

		$this->token = [
				'iat'  => $this->issuedAt,		// Issued at: time when the token was generated
				'aud'  => aud(),
				'jti'  => $this->tokenId,       // Json Token Id: an unique identifier for the token
				'iss'  => $this->issuer,       	// Issuer
				'nbf'  => $this->notBefore,     // Not before
				'exp'  => $this->expire,        // Expire
				'data' => $data					// Custom data
		];


	}

	public function initializeToken() {
		$this->token = array(
				"iss" => BASE_PATH,

				"iat" => time(),
				"nbf" => time() + GF_JWT_AUTHENTICATION_EXPIRATION,
				"data" => $this->data
		);
	}

	public function getJWTToken() {
		$jwt = JWT::encode($this->token,$this->key,'HS512');
		return $jwt;

	}

	private static function aud()
	{
		$aud = GFSessionController::getInstance()->getSessionModel()->getUserIp();

		$aud .= @$_SERVER['HTTP_USER_AGENT'];
		$aud .= gethostname();

		return sha1($aud);
	}
	/**
	 * IMPORTANT:
	 * You must specify supported algorithms for your application. See
	 * https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40
	 * for a list of spec-compliant algorithms.
	 */
	$jwt = JWT::encode($token, $key);
	$decoded = JWT::decode($jwt, $key, array('HS256'));


	print_r($decoded); die(); //TODO: Diego pre
}