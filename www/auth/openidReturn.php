<?php 

require_once '../../lib/Util.php';

list($identity, $user) = OpenID::finishAuth();
if (!$identity) {
  Util::redirect('login');
}

$loggedInUser = Session::getUser();
if ($loggedInUser) {
  // user is logged in and trying to link a new OpenID to their account
  if (!$user->id) {
    // good, we haven't seen this OpenID  before
    $identity->userId = $loggedInUser->id;
    $identity->save();
    FlashMessage::add(_('New OpenID linked to your account.'), 'info');
  } else if ($user->id == $loggedInUser->id) {
    FlashMessage::add(_('This OpenID is already linked to your account.'), 'warning');
  } else {
    FlashMessage::add(_("This OpenID is already linked to another account. We cannot merge accounts. " .
                        "Please close the other account before linking the OpenID to this one."));
  }
  Util::redirect('account');
} else if ($user->id) {
  // known user is logging in
  Session::login($user);
} else {
  // first time seeing this OpenID; ask the user to choose a username.
  Session::saveFirstLogin($identity, $user);
  FlashMessage::add(_('Please pick a username to complete your login.'), 'warning');
  Util::redirect('account');
}

?>
