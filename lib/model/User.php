<?php

class User extends BaseObject {

  function getDisplayName() {
    $s = $this->identity;
    if ($this->username) {
      $s = $this->username;
    } else if ($this->name) {
      $s = $this->name;
    } else if ($this->email) {
      $s = $this->email;
    }
    return StringUtil::shortenString($s, 30);
  }

  /**
   * Validates a user for correctness. If $flashErrors is set, then sets flash error messages.
   */
  function validate($flashErrors = true) {
    $valid = true;

    if (!preg_match("/^[-._0-9\p{L}]{3,20}$/u", $this->username)) {
      $valid = false;
      if ($flashErrors) {
        FlashMessage::add(_("The username must be between 3 and 20 characters long and consist of letters, digits, '-', '.' and '_'."));
      }
    }

    $otherUser = Model::factory('User')->where('username', $this->username)->find_one();
    if ($otherUser && ($otherUser->id != $this->id)) {
      $valid = false;
      if ($flashErrors) {
        FlashMessage::add(_('This username is already in use.'));
      }
    }

    if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      $valid = false;
      if ($flashErrors) {
        FlashMessage::add(_('The email address is invalid.'));
      }
    }

    return $valid;
  }

}

?>
