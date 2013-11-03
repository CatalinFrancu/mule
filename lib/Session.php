<?php

define("ONE_MONTH_IN_SECONDS", 30 * 86400);

/**
 * This class handles session-specific variables.
 **/
class Session {

  static function init() {
    if (isset($_COOKIE[session_name()])) {
      session_start();
    }
    if (self::getUser() == null) {
      self::loadUserFromCookie();
    }
  }

  static function get($name, $default = null) {
    return (isset($_SESSION) && array_key_exists($name, $_SESSION)) ? $_SESSION[$name] : $default;
  }

  static function set($var, $value) {
    // Lazy start of the session so we don't send a PHPSESSID cookie unless we have to
    if (!isset($_SESSION)) {
      session_start();
    }
    $_SESSION[$var] = $value;
  }

  static function unsetVariable($var) {
    if (isset($_SESSION)) {
      unset($_SESSION[$var]);
      if (!count($_SESSION)) {
        // Note that this will prevent us from creating another session during this same request.
        // This does not seem to cause a problem at the moment.
        self::kill();
      }
    }
  }

  static function getUser() {
    return self::get('user');
  }

  private static function kill() {
    if (!isset($_SESSION)) {
      session_start(); // It has to have been started in order to be destroyed.
    }
    session_unset();
    session_destroy();
    if (ini_get("session.use_cookies")) {
      setcookie(session_name(), '', time() - 3600, '/'); // expire it
    }
  }

  static function login($user) {
    self::set('user', $user);
    $cookie = Model::factory('LoginCookie')->create();
    $cookie->userId = $user->id;
    $cookie->value = StringUtil::randomCapitalLetters(12);
    $cookie->save();
    $cookieName = Config::get('general.loginCookieName');
    setcookie($cookieName, $cookie->value, time() + ONE_MONTH_IN_SECONDS, '/');
    Util::redirect(Util::$wwwRoot);
  }

  static function logout() {
    $cookieName = Config::get('general.loginCookieName');
    if (array_key_exists($cookieName, $_COOKIE)) {
      $cookie = LoginCookie::get_by_value($_COOKIE[$cookieName]);
      if ($cookie) {
        $cookie->delete();
      }
    }
    setcookie($cookieName, NULL, time() - 3600, '/');
    unset($_COOKIE[$cookieName]);
    self::kill();
    Util::redirect(Util::$wwwRoot);
  }

  static function loadUserFromCookie() {
    $cookieName = Config::get('general.loginCookieName');
    if (!array_key_exists($cookieName, $_COOKIE)) {
      return;
    }
    $cookie = LoginCookie::get_by_value($_COOKIE[$cookieName]);
    $user = $cookie ? User::get_by_id($cookie->userId) : null;
    if ($user) {
      self::set('user', $user);
    } else {
      // The cookie is invalid
      setcookie($cookieName, NULL, time() - 3600, '/');
      unset($_COOKIE[$cookieName]);
      if ($cookie) {
        $cookie->delete();
      }
    }
  }

}

?>
