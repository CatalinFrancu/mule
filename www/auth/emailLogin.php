<?php 

require_once '../../lib/Util.php';

$email = Util::getRequestParameter('email');
$token = Util::getRequestParameter('token');
$submitButton = Util::getRequestParameter('submitButton');

if ($submitButton) {
  $user = User::get_by_email($email);

  if (!$email) {
    FlashMessage::add(_('Please enter an email address.'));
  } else if (!$user) {
    FlashMessage::add(_('There is no account with that email address.'));
  } else {
    // Send e-mail here
    sendEmailToken($user);
    FlashMessage::add(_('We have sent you a one-time login code. Please check your email to complete the login process.'), 'info');
    Util::redirect('emailLogin.php'); // To clear all data
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
