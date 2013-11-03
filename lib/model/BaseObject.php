<?php

class BaseObject extends Model {
  /**
   * Accept calls like User::get_by_email($email) and User::get_all_by_email($email)
   **/
  static function __callStatic($name, $arguments) {
    $getBy = substr($name, 0, 7) == 'get_by_';
    $getAllBy = substr($name, 0, 11) == 'get_all_by_';
    if ($getBy || $getAllBy) {
      $fieldString = substr($name, $getBy ? 7 : 11);
      $fields = explode('_', $fieldString);
      if (count($fields) != count($arguments)) {
        self::__die('incorrect number of arguments', $name, $arguments);
      }
      $clause = Model::factory(get_called_class());
      foreach ($fields as $i => $field) {
        $clause = $clause->where($field, $arguments[$i]);
      }
      return $getBy ? $clause->find_one() : $clause->find_many();
    }
    self::__die('cannot handle method', $name, $arguments);
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
