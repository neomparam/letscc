<?php

session_start();

$re_url = ( trim($_GET["re_url"]) ) ? trim($_GET["re_url"]):"/";

//$_SESSION['return_url'] = $_SERVER['HTTP_REFERER'];
$_SESSION['return_url'] = $re_url;

require_once('../../../lib/oauth/facebook-php-sdk/src/facebook.php');
require_once('../../../lib/config.php');

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
	'appId'  => FACEBOOK_APPID,
	'secret' => FACEBOOK_SECRET,
));

$loginUrl = $facebook->getLoginUrl(
	array(
		'redirect_uri' => FACEBOOK_CALLBACK
	)
);

$_SESSION['favorite_cidx'] ="";
$_SESSION['favorite_keyword'] ="";

if( isset($_GET['c_idx']) && $_GET['c_idx'] != "" ) {

	$_SESSION['favorite_cidx'] = $_GET['c_idx'];
	$_SESSION['favorite_keyword'] = $_GET['keyword'];
}

header('Location: '.$loginUrl);

?>
