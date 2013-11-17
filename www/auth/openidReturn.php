<?php 

require_once '../../lib/Util.php';
Util::requireNotLoggedIn();

list($identity, $user) = OpenID::finishAuth();
if (!$identity) {
  Util::redirect('login');
}

if ($user->id) {
  Session::login($user);
} else {
  // Ask the user to choose a username.
  Session::saveFirstLogin($identity, $user);
  FlashMessage::add(_('Please pick a username to complete your login.'), 'warning');
  Util::redirect('account.php');
}

?>
