<?php

require_once '../lib/Util.php';

SmartyWrap::assign('pageTitle', _('Home page'));
SmartyWrap::display('index.tpl');

?>
