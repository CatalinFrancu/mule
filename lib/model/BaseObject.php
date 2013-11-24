<?php

class BaseObject extends Model {
  const ACTION_SELECT = 1;
  const ACTION_SELECT_ALL = 2;
  const ACTION_DELETE_ALL = 3;

  /**
   * Accept calls like User::get_by_email($email) and User::get_all_by_email($email)
   **/
  static function __callStatic($name, $arguments) {
    $action = null;
    if (substr($name, 0, 7) == 'get_by_') {
      return self::action(substr($name, 7), $arguments, self::ACTION_SELECT);
    } else if (substr($name, 0, 11) == 'get_all_by_') {
      return self::action(substr($name, 11), $arguments, self::ACTION_SELECT_ALL);
    } else if (substr($name, 0, 14) == 'delete_all_by_') {
      self::action(substr($name, 14), $arguments, self::ACTION_DELETE_ALL);
    } else {
      self::__die('cannot handle method', $name, $arguments);
    }
  }

  private static function action($fieldString, $arguments, $action) {
    $fields = explode('_', $fieldString);
    if (count($fields) != count($arguments)) {
      self::__die('incorrect number of arguments', $action, $arguments);
    }
    $clause = Model::factory(get_called_class());
    foreach ($fields as $i => $field) {
      $clause = $clause->where($field, $arguments[$i]);
    }

    switch ($action) {
      case self::ACTION_SELECT: return $clause->find_one();
      case self::ACTION_SELECT_ALL: return $clause->find_many();
      case self::ACTION_DELETE_ALL:
        $objects = $clause->find_many();
        foreach ($objects as $o) {
          $o->delete();
        }
        break;
    }
  }

  static function __die($message, $name, $arguments) {
    print "BaseObject::__callStatic() error: $message<br/>\n";
    print "Function name: $name<br/>\nArguments: ";
    print_r($arguments);
    die();
  }

  function save() {
    /* Auto-save created and modified fields */
    $this->modified = time();
    if (!$this->created) {
      $this->created = $this->modified;
    }
    return parent::save();
  }
}

?>
