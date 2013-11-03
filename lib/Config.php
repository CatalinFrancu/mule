<?php

/**
 * This class loads and queries an INI file. All the setings must go under a section, such as
 * [general]
 * database = "mysql://root@localhost/mydatabase"
 *
 * [someSection]
 * someKey = someValue
 * someOtherKey = someOtherValue
 **/
class Config {
  private static $config = array();

  static function load($fileName) {
    self::$config = parse_ini_file($fileName, true);
  }

  static function get($key, $default = null) {
    list($section, $name) = explode('.', $key, 2);
    if (array_key_exists($section, self::$config) && array_key_exists($name, self::$config[$section])) {
      return self::$config[$section][$name];
    } else {
      return $default;
    }
  }
}

?>
