<?php

namespace Dashifen\Session;

/**
 * Interface Session
 *
 * @package Dashifen\Session
 */
interface SessionInterface {
	/**
	 * Returns true if the session has recorded the logging in of an authenticated visitor
	 *
	 * @return bool
	 */
	public function isAuthenticated(): bool;
	
	/**
	 * Records the logging in of an authenticated visitor.
	 *
	 * @param string $username
	 * @param array  $parameters
	 *
	 * @return void
	 */
	public function login(string $username, array $parameters = []): void;
	
	/**
	 * Removes information about a visitor's authenticated session
	 *
	 * @return void
	 */
	public function logout();
	
	/**
	 * Destroys a session.
	 *
	 * @return void
	 */
	public function destroy(): void;
	
	/**
	 * Effectively, returns $_SESSION[$index] or $default
	 *
	 * @param string $index
	 * @param mixed $default
	 *
	 * @return mixed
	 */
	public function get(string $index, $default = null);
	
	/**
	 * Effectively, determines if $_SESSION[$index] exists
	 *
	 * @param string $index
	 *
	 * @return bool
	 */
	public function exists(string $index): bool;
	
	/**
	 * Effectively, $_SESSION[$index] = $value
	 *
	 * @param string $index
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function set(string $index, $value): void;
	
	/**
	 * Effectively, unset($_SESSION[$index])
	 *
	 * @param $index
	 *
	 * @return void
	 */
	public function remove(string $index): void;
}
