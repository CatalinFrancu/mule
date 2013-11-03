<?php

Util::init();

/**
 * This class is the only one that needs to be called explicitly from every web page or script.
 * It loads everything else on demand.
 **/
class Util {
  static $rootPath;
  static $wwwRoot;
  
  static function init() {
    ini_set('display_errors', 'On');
    self::definePaths();
    spl_autoload_register('self::autoloadClasses');
    Config::load(self::$rootPath . "/mule.conf");
    setlocale(LC_ALL, Config::get('general.locale'));
    require_once self::$rootPath . '/lib/idiorm/idiorm.php';
    require_once self::$rootPath . '/lib/idiorm/paris.php';
    require_once self::$rootPath . '/lib/smarty/Smarty.class.php';
    Db::init(Config::get('general.database'));
    Session::init();
    FlashMessage::restoreFromSession();
    SmartyWrap::init(Config::get('general.smartyClass'));
  }
  
  private static function definePaths() {
    self::$rootPath = realpath(__DIR__ . '/..');
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $pos = strrpos($scriptName, '/www/');
    self::$wwwRoot = ($pos === false) ? '/' : substr($scriptName, 0, $pos + 5);
  }

  static function autoloadClasses($className) {
    $fileName = self::$rootPath . "/lib/{$className}.php";
    if (file_exists($fileName)) {
      require_once($fileName);
    }
    $fileName = self::$rootPath . "/lib/model/{$className}.php";
    if (file_exists($fileName)) {
      require_once($fileName);
    }
  }

  static function getFullServerUrl() {
    $host = $_SERVER['SERVER_NAME'];
    $port =  $_SERVER['SERVER_PORT'];
    $path = self::$wwwRoot;

    return ($port == '80') ? "http://$host$path" : "http://$host:$port$path";
  }

  static function requireNotLoggedIn() {
    if (Session::getUser()) {
      self::redirect(self::$wwwRoot);
    }
  }

  static function requireLoggedIn() {
    if (!Session::getUser()) {
      FlashMessage::add('Pentru a avea acces la această pagină, trebuie să vă autentificați', 'warning');
      self::redirect(self::$wwwRoot . 'auth/login');
    }
  }

  static function requireAdmin() {
    self::requireLoggedIn();
    if (!Session::getUser()->admin) {
      FlashMessage::add('Nu aveți acces la această pagină.');
      self::redirect(self::$wwwRoot);
    }
  }

  static function redirect($location) {
    FlashMessage::saveToSession();
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: $location");
    exit;
  }

  static function getRequestParameter($name, $default = null) {
    return array_key_exists($name, $_REQUEST) ? StringUtil::sanitize($_REQUEST[$name]) : $default;
  }

  static function getCheckboxValue($name, $default = false) {
    return array_key_exists($name, $_REQUEST);
  }

  /**
   * Remove some ugly GET parameters and redirect to the remaining URL.
   **/
  static function hideRequestParameters(/** Variable-length argument list **/) {
    $needToRedirect = false;
    $newQueryString = '';
    $hide = func_get_args();

    foreach ($_GET as $name => $value) {
      if (in_array($name, $hide)) {
        $needToRedirect = true;
      } else {
        $newQueryString .= $newQueryString ? '&' : '?';
        $newQueryString .= "$name=$value";
      }
    }

    if ($needToRedirect) {
      self::redirect($_SERVER['PHP_SELF'] . $newQueryString);
    }
  }
}

?>
