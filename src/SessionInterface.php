<?php

namespace Dashifen\Session;

/**
 * Interface Session
 *
 * @package Dashifen\Session
 */
interface SessionInterface
{
  public const string DEFAULT_INDEX = 'Dashifen\Session\Session::index';
  public const string AUTHENTICATED = 'Dashifen\Session\Session::authentic';
  public const string USERNAME = 'Dashifen\Session\Session::username';
  
  /**
   * Returns true if the user's current session is authenticated.
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
   * @return SessionInterface
   */
  public function login(string $username, array $parameters = []): SessionInterface;
  
  /**
   * Removes information about a visitor's authenticated session
   *
   * @return SessionInterface
   */
  public function logout(): SessionInterface;
  
  /**
   * Fully destroys a session; not just our data, all data.
   *
   * @return void
   */
  public function destroy(): void;
  
  /**
   * Returns the data we've stored at $index or $default.
   *
   * @param string $index
   * @param mixed  $default
   *
   * @return mixed
   */
  public function get(string $index, mixed $default = null): mixed;
  
  /**
   * Effectively, determines if $_SESSION[$index] exists
   *
   * @param string $index
   *
   * @return bool
   */
  public function exists(string $index): bool;
  
  /**
   * Returns true if the specified index exists within our session.
   *
   * @param string $index
   * @param mixed  $value
   *
   * @return SessionInterface
   */
  public function set(string $index, mixed $value): SessionInterface;
  
  /**
   * Removes the value stored at $index.
   *
   * @param string $index
   *
   * @return SessionInterface
   */
  public function remove(string $index): SessionInterface;
  
  /**
   * Returns the session information managed herein
   *
   * @return array
   */
  public function getSession(): array;
}
