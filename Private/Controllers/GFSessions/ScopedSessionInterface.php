<?php
namespace Controllers\GFSessions;


interface ScopedSessionInterface {
	const GF_GLOBAL_SESSION = "gf_session";
	const GF_DEFAULT_SESSION = "gf_default";
	const CSRF_SCOPE = "gf_csrf";
	
	public function getSession();
	public function put($key, $value);
	public function safePut($key, $value);
	public function get($key);
	public function getAndDelete($key);
	public function delete($key);
	
}