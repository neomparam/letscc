<?php
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
session_start();
require_once('../../../../lib/oauth/twitteroauth/twitteroauth.php');
require_once('../../../../lib/config.php');

require_once('../../../../lib/class.dbConnect.php');
require_once('../../../../lib/class.members.php');
require_once('../../../../lib/class.favorites.php');

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  $_SESSION['oauth_status'] = 'oldtoken';
  header('Location: ./clearsessions.php');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;

$return_url = ($_SESSION['return_url']!="")?$_SESSION['return_url']:"/index.php";

/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
  /* The user has been verified and the access tokens can be saved for future use */

	$content = $connection->get('account/verify_credentials');

	$DB = new dbConn();
	$Member = new clsMembers( $DB->getConnection() );

	$oauth_type = "twitter";
	//세션 저장
	$result = $Member->getOauthMemberIdx( $oauth_type, $content->id_str );

	if( $result['r'] == 'success' ) {
		$_SESSION['USER_IDX'] = $result['idx'];
		$_SESSION['USER_TYPE'] = $oauth_type;
		$_SESSION['USER_ID'] = $content->id_str;
		$_SESSION['USER_NAME'] = $content->name;
		$_SESSION['USER_IMAGE'] = $content->profile_image_url;
		$_SESSION['USER_AGREE'] = $result['policy_agree'];

		//my favorite 정보가 있다면 저장 후 my favorite 페이지로 이동
		$c_idx ="";
		$keyword ="";

		if( !isset($_SESSION['favorite_cidx']) || $_SESSION['favorite_cidx'] != "" ) {
			$c_idx = $_SESSION['favorite_cidx'];
			$keyword = $_SESSION['favorite_keyword'];
			$_SESSION['favorite_cidx'] = ""; unset($_SESSION['favorite_cidx']);
			$_SESSION['favorite_keyword'] = ""; unset($_SESSION['favorite_keyword']);
		}
	} else {
		header('Location: ./clearsessions.php');
	}

	//콘텐츠 IDX 가 넘어오면 즐겨찾기에 추가 후 My즐겨찾기 페이지로 이동
	if( $c_idx != "" ) {
		$Favorite = new clsFavorites( $DB->getConnection() );

		$arr = array(
			"m_idx"=>$_SESSION['USER_IDX'],
			"c_idx"=>$c_idx,
			"search_word"=>$keyword,
			"tags"=>""
		);
		$f_result = $Favorite->save( $arr );
		$f_idx = $f_result['idx']; //데이터가 있으면 callback 페이지에서 my즐겨찾기 페이지로 이동.

		//회원가입에 동의 하지 않았다면 동의 페이지로 이동한다.
		if( $_SESSION['USER_AGREE'] == "n" ) {
			header('Location: /member/join_confirm.php?re_url=/my_favorite.php?f_idx='.$f_idx );
		} else {
			header('Location: /my_favorite.php?f_idx='.$f_idx);
		}
	} else {
		//회원가입에 동의 하지 않았다면 동의 페이지로 이동한다.
		if( $_SESSION['USER_AGREE'] == "n" ) {
			header('Location: /member/join_confirm.php?re_url='.$return_url);
		} else {
			header('Location: '.$return_url);
		}
	}

} else {
	/* Save HTTP status for error dialog on connnect page.*/
	header('Location: ./clearsessions.php');
}
