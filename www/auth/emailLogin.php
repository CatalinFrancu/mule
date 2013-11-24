<?php 

define("ONE_HOUR_IN_SECONDS", 3600);

require_once '../../lib/Util.php';
Util::requireNotLoggedIn();

$email = Util::getRequestParameter('email');
$token = Util::getRequestParameter('token');
$submitButton = Util::getRequestParameter('submitButton');

if ($submitButton) {
  // user typed an email address and wants an token
  $user = User::get_by_email($email);

  if (!$email) {
    FlashMessage::add(_('Please enter an email address.'));
  } else if (!$user) {
    FlashMessage::add(_('There is no account with that email address.'));
  } else {
    LoginToken::delete_all_by_userId($user->id);
    sendEmailToken($user);
    FlashMessage::add(_('We have sent you a one-time login code. Please check your email to complete the login process.'), 'info');
    Util::redirect('emailLogin.php'); // To clear all data
  }

} else if ($email && $token) {
  // user clicked on a link and wants to log in
  $lt = Model::factory('LoginToken')->where('token', $token)->where_gte('created', time() - ONE_HOUR_IN_SECONDS)->find_one();
  $user = User::get_by_email($email);

  if (!$lt || !$user || ($lt->userId != $user->id)) {
    FlashMessage::add(_('Invalid token'));
  } else {
    LoginToken::delete_all_by_userId($user->id);
    FlashMessage::add(_('You have successfully logged in. Please remember to link an OpenID to your account.'), 'warning');
    Session::login($user);
  }
}

SmartyWrap::assign('email', $email);
SmartyWrap::assign('pageTitle', _('Email login'));
SmartyWrap::display('auth/emailLogin.tpl');

/****************************************************************************/

function sendEmailToken($user) {
  $lt = Model::factory('LoginToken')->create();
  $lt->userId = $user->id;
  $lt->token = StringUtil::randomCapitalLetters(20);
  $lt->save();

  SmartyWrap::assign('homePage', Util::getFullServerUrl());
  SmartyWrap::assign('token', $lt->token);
  SmartyWrap::assign('email', $user->email);
  SmartyWrap::assign('signature', Config::get('email.signature'));
  $body = SmartyWrap::fetchEmail('emailLogin.tpl');

  $headers = Config::get('email.fromHeader') . "\r\n" .
    Config::get('email.replyToHeader') . "\r\n" .
    "Content-Type: text/plain; charset=UTF-8";

  if (Config::get('email.enabled')) {
    mail($user->email, _("Email login token"), $body, $headers);
  }
}

?>
