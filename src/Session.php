<?php

namespace Dashifen\Session;

/**
 * Class Session
 *
 * @package Dashifen\Session
 */
class Session implements SessionInterface {
	
	/**
	 * @var string $index ;
	 */
	private $index;
	
	/**
	 * Session constructor.
	 *
	 * @param string $index
	 *
	 * @throws SessionException
	 */
	public function __construct(string $index = "") {
		$this->index = empty($index) ? uniqid() : $index;
		
		// if we haven't started the session already, we'll make sure we do so
		// now.  if we can't start it, we toss an exception and how the rest of
		// our app knows what to do.
		
		if (session_id() === "") {
			if (!session_start()) {
				throw new SessionException("Unable to start session");
			}
		}
		
		// to help avoid colliding with other session information, we're going to
		// put everything we manage into an array identified by the index we were
		// sent or generated above.
		
		$_SESSION[$this->index] = [];
	}
	
	/**
	 * @return bool
	 */
	public function isAuthenticated(): bool {
		return $this->exists("AUTHENTICATED");
	}
	
	/**
	 * @param string $username
	 * @param array  $parameters
	 */
	public function login(string $username, array $parameters = []): void {
		session_regenerate_id();
		
		// this function is not intended to perform the authentication or authorization
		// routines.  instead, it assumes these are done and simply stores the results of
		// those routines in the session as follows.
		
		$this->set("AUTHENTICATED", true);
		$this->set("USERNAME", $username);
		foreach ($parameters as $i => $value) {
			$this->set($i, $value);
		}
	}
	
	/**
	 * Destroys a session.
	 *
	 * @return void
	 */
	public function logout() {
		$_SESSION[$this->index] = [];
	}
	
	/**
	 * Destroys a session.
	 *
	 * @return void
	 */
	public function destroy(): void {
		
		// see example here http://goo.gl/nBVl0
		
		$this->logout();
		$p = session_get_cookie_params();
		setcookie(session_name(), "", time() - 42000, $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
		session_destroy();
	}
	
	/**
	 * @param  string $index
	 * @param string  $default
	 *
	 * @return mixed|null
	 */
	public function get(string $index, $default = null) {
		return $this->exists($index) ? $_SESSION[$this->index][$index] : $default;
	}
	
	/**
	 * @param string $index
	 *
	 * @return bool
	 */
	public function exists(string $index): bool {
		return isset($_SESSION[$this->index][$index]);
	}
	
	/**
	 * @param string $index
	 * @param        $value
	 */
	public function set(string $index, $value): void {
		$_SESSION[$this->index][$index] = $value;
	}
	
	/**
	 * @param string $index
	 */
	public function remove(string $index): void {
		unset($_SESSION[$this->index][$index]);
	}
	
}
