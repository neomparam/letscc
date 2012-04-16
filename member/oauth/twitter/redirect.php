<?php

/* Start session and load library. */
session_start();
require_once('../../../lib/oauth/twitteroauth/twitteroauth/twitteroauth.php');
require_once('../../../lib/config.php');

/* Build TwitterOAuth object with client credentials. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
 
/* Get temporary credentials. */
$request_token = $connection->getRequestToken(TWITTER_CALLBACK);

/* Save temporary credentials to session. */
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];

$re_url = ( trim($_GET["re_url"]) ) ? trim($_GET["re_url"]):"/";

//$_SESSION['return_url'] = $_SERVER['HTTP_REFERER'];
$_SESSION['return_url'] = $re_url;

//my favorite 정보
$_SESSION['favorite_cidx'] ="";
$_SESSION['favorite_keyword'] ="";

if( isset($_GET['c_idx']) || $_GET['c_idx'] != "" ) {
	$_SESSION['favorite_cidx'] = $_GET['c_idx'];
	$_SESSION['favorite_keyword'] = $_GET['keyword'];
}

/* If last connection failed don't display authorization link. */
switch ($connection->http_code) {
  case 200:
    /* Build authorize URL and redirect user to Twitter. */
    $url = $connection->getAuthorizeURL($token);
    header('Location: ' . $url); 
    break;
  default:
    /* Show notification if something went wrong. */
    echo 'Could not connect to Twitter. Refresh the page or try again later.';
	header('Location: ./clearsessions.php');
}
