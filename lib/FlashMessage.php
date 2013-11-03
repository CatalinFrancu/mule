<?php

class FlashMessage {
  public static $message = '';
  public static $type = '';
  private static $anyErrors = false;

  static function add($message, $type = 'error') {
    self::$message .= $message . '<br/>';
    self::$type = $type;
    self::$anyErrors |= ($type == 'error');
  }

  static function getMessage() {
    return self::$message ? self::$message : null;
  }

  static function hasErrors() {
    return self::$anyErrors;
  }

  static function getMessageType() {
    return self::$type ? self::$type : null;
  }

  static function saveToSession() {
    if (self::$message) {
      Session::set('flashMessage', self::$message);
      Session::set('flashMessageType', self::$type);
    }
  }

  static function restoreFromSession() {
    if (($message = Session::get('flashMessage')) && ($type = Session::get('flashMessageType'))) {
      self::$message = $message; // Already has a trailing <br/>
      self::$type = $type;
      Session::unsetVariable('flashMessage');
      Session::unsetVariable('flashMessageType');
    }
  }
}

?>
