<?php

require_once('common/HTMLView.php');
require_once('view/TimeView.php');
require_once('ctrl/LoginController.php');

ini_set('session.gc_maxlifetime', 60*60*24*30); //Sessionens livstid = 30dgr
ini_set('session.cache_limiter', 180);
session_name("MySession");

$a = session_id();
if(empty($a)){
	session_start();
}

$lc = new \controller\LoginController();
$t = new \view\TimeView();

$htmlBody = $lc->doLogin();
$time = $t->setTime();

$view = new \common\HTMLView();
$view->echoHTML($htmlBody, $time);