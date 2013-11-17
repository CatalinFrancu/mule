<?php 

/**
 * Displays or edits the user's account information. Can be invoked in two situations:
 * - when a logged in user clicks on their "my account" link;
 * - when a user logs in using an OpenID not seen before, in which case they must choose a username
 **/

require_once '../../lib/Util.php';

$username = Util::getRequestParameter('username');
$name = Util::getRequestParameter('name');
$email = Util::getRequestParameter('email');
$submitButton = Util::getRequestParameter('submitButton');

// Determine whether we have a first-time login
list($identity, $user) = Session::retrieveFirstLogin();

if ($user) {
  $firstLogin = true;
  Util::requireNotLoggedIn();
} else {
  $firstLogin = false;
  Util::requireLoggedIn();
  $user = User::get_by_id(Session::getUser()->id); // cannot cache resource objects, need to refresh them
}

// Save action
if ($submitButton) {
  if ($firstLogin) {
    $user->username = $username;
  }
  $user->name = $name;
  $user->email = $email;
  if ($firstLogin && !$identity) {
    FlashMessage::add(_('Could not find your OpenID; did you really log in?'));
  }
  $user->validate();
  
  if (!FlashMessage::hasErrors()) {
    $user->save();
    if ($firstLogin) {
      $identity->userId = $user->id;
      $identity->save();
      Session::unsetVariable('firstLogin');
      Session::login($user);
    } else {
      Session::set('user', $user); // cache the new values
      FlashMessage::add(_('Changes saved.'), 'info');
      Util::redirect('account');
    }
  }
}

SmartyWrap::assign('editUser', $user);
SmartyWrap::assign('firstLogin', $firstLogin);
SmartyWrap::assign('pageTitle', _('my account'));
SmartyWrap::display('auth/account.tpl');

?>
