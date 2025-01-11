<?php

namespace Dashifen\Session;

/**
 * Class Session
 *
 * @package Dashifen\Session
 */
class Session implements SessionInterface
{
  /**
   * Session constructor.
   *
   * @param string $index
   *
   * @throws SessionException
   */
  public function __construct(private string $index = SessionInterface::DEFAULT_INDEX)
  {
    $this->index = empty($index) ? $this->getIndex() : $index;
    
    // if we haven't started the session already, we'll make sure we do so
    // now.  if we can't start it, we toss an exception and how the rest of
    // our app knows what to do.
    
    if (session_id() === '' && !session_start()) {
      throw new SessionException('Unable to start session', SessionException::CANNOT_START);
    }
    
    // to help avoid colliding with other session information, we're going to
    // put everything we manage into an array identified by the index we were
    // sent or generated above.  but, since we might be reconnecting to a
    // session already in progress, we'll only want to create that array if
    // it's not already present.
    
    if (!isset($_SESSION[$this->index]) || !is_array($_SESSION[$this->index])) {
      $_SESSION[$this->index] = [];
    }
  }
  
  /**
   * Returns an index we'll use to identify our data in this session.
   *
   * @return string
   */
  protected function getIndex(): string
  {
    // if we're reconnecting to a visitor's session, then the index is
    // actually stored in the session for us to find.  if this is the
    // first time we've created a session for this visitor, we'll create
    // one.  our interface defines the index at which we find our index,
    // the index-index if you will.
    
    return $_SESSION[SessionInterface::DEFAULT_INDEX] ?? uniqid();
  }
  
  /**
   * Returns true if the user's current session is authenticated.
   *
   * @return bool
   */
  public function isAuthenticated(): bool
  {
    return $this->exists(SessionInterface::AUTHENTICATED);
  }
  
  /**
   * Returns the authenticated username or null.
   *
   * @return string|null
   */
  public function getUsername(): ?string
  {
    return $this->isAuthenticated()
      ? $this->get(SessionInterface::USERNAME, null)
      : null;
  }
  
  
  /**
   * Returns true if the specified index exists within our session.
   *
   * @param string $index
   *
   * @return bool
   */
  public function exists(string $index): bool
  {
    return isset($_SESSION[$this->index][$index]);
  }
  
  /**
   * Records the specified username as the current authenticated user.
   *
   * @param string $username
   * @param array  $parameters
   *
   * @return SessionInterface
   */
  public function login(string $username, array $parameters = []): SessionInterface
  {
    session_regenerate_id();
    
    // this function is not intended to perform the authentication or authorization
    // routines.  instead, it assumes these are done and simply stores the results of
    // those routines in the session as follows.
    
    foreach ($parameters as $i => $value) {
      $this->set($i, $value);
    }
    
    return $this
      ->set(SessionInterface::USERNAME, $username)
      ->set(SessionInterface::AUTHENTICATED, true);
  }
  
  /**
   * Records the given value at the specified index.
   *
   * @param string $index
   * @param        $value
   *
   * @return SessionInterface
   */
  public function set(string $index, $value): SessionInterface
  {
    $_SESSION[$this->index][$index] = $value;
    return $this;
  }
  
  /**
   * Fully destroys a session; not just our data, all data.
   *
   * @return void
   */
  public function destroy(): void
  {
    // see https://www.php.net/manual/en/function.session-destroy.php
    
    $this->logout();
    $p = session_get_cookie_params();
    setcookie(session_name(), "", time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    session_destroy();
  }
  
  /**
   * Removes information about a visitor's authenticated session
   *
   * @return SessionInterface
   */
  public function logout(): SessionInterface
  {
    $_SESSION[$this->index] = [];
    return $this;
  }
  
  /**
   * Returns the data we've stored at $index or $default.
   *
   * @param string $index
   * @param mixed  $default
   *
   * @return mixed
   */
  public function get(string $index, mixed $default = null): mixed
  {
    return $this->exists($index) ? $_SESSION[$this->index][$index] : $default;
  }
  
  /**
   * Removes the value stored at $index.
   *
   * @param string $index
   *
   * @return SessionInterface
   */
  public function remove(string $index): SessionInterface
  {
    unset($_SESSION[$this->index][$index]);
    return $this;
  }
  
  /**
   * Returns the session information managed herein
   *
   * @return array
   */
  public function getSession(): array
  {
    return $_SESSION[$this->index];
  }
}
