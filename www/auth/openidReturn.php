<?php 

require_once '../../lib/Util.php';
Util::requireNotLoggedIn();

$data = OpenID::finishAuth();
if (!$data) {
  Util::redirect('login');
}

$user = User::updateFromOpenId($data);
Session::login($user);

?>
